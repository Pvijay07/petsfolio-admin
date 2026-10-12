<?php

namespace App\Services;

class ClientCallRatingService
{
    /**
     * Normalize raw AI client-provider call analysis payload for DB storage.
     *
     * @param array $rawAnalysis Output from analyze_client_call.py
     * @param string $defaultService Default booked service (walking, grooming, training, sitting)
     * @return array Formatted array matching call_analysis table schema
     */
    public function formatAnalysisPayload(array $rawAnalysis, string $defaultService = 'walking'): array
    {
        $transcript        = $rawAnalysis['transcript'] ?? '';
        $summary           = $rawAnalysis['summary'] ?? '';
        $serviceType       = strtolower($rawAnalysis['service_type'] ?? $defaultService);

        // Policy violation flags
        $offPlatformPayment    = (!empty($rawAnalysis['off_platform_payment']) || !empty($rawAnalysis['platform_bypass']) || !empty($rawAnalysis['gst_bypass'])) ? 1 : 0;
        $contactSharing        = !empty($rawAnalysis['contact_sharing']) ? 1 : 0;
        $outsideServiceRequest = !empty($rawAnalysis['outside_service_request']) ? 1 : 0;
        $refundRequested       = !empty($rawAnalysis['refund_requested']) ? 1 : 0;
        $hasComplaint          = !empty($rawAnalysis['has_complaint']) ? 1 : 0;
        $hasAbuse              = (!empty($rawAnalysis['inappropriate_language']) || !empty($rawAnalysis['has_abuse'])) ? 1 : 0;

        // Sentiments (preserve Non-Compliant for provider)
        $rawCustSent = $rawAnalysis['customer_sentiment'] ?? '';
        $rawProvSent = $rawAnalysis['provider_sentiment'] ?? '';

        if (!empty($rawCustSent)) {
            $customerSentiment = ucfirst(strtolower($rawCustSent));
        } else {
            $customerSentiment = ($hasComplaint || $hasAbuse || $refundRequested) ? 'Negative' : 'Positive';
        }

        if (!empty($rawProvSent)) {
            if (strtolower($rawProvSent) === 'non-compliant' || strtolower($rawProvSent) === 'non_compliant') {
                $providerSentiment = 'Non-Compliant';
            } else {
                $providerSentiment = ucfirst(strtolower($rawProvSent));
            }
        } else {
            if ($offPlatformPayment || $contactSharing) {
                $providerSentiment = 'Non-Compliant';
            } elseif ($hasAbuse) {
                $providerSentiment = 'Negative';
            } else {
                $providerSentiment = 'Positive';
            }
        }

        // Service relevance metrics
        $serviceRelevanceScore = isset($rawAnalysis['service_relevance_score']) ? (float)$rawAnalysis['service_relevance_score'] : 9.0;
        $serviceRelatedPct     = isset($rawAnalysis['service_related_pct']) ? (float)$rawAnalysis['service_related_pct'] : 90.0;
        $unrelatedPct          = isset($rawAnalysis['unrelated_pct']) ? (float)$rawAnalysis['unrelated_pct'] : max(0.0, 100.0 - $serviceRelatedPct);

        // Overall rating calculation & category
        if (isset($rawAnalysis['ai_rating_score'])) {
            $overallRating = (float)$rawAnalysis['ai_rating_score'];
        } elseif (isset($rawAnalysis['overall_rating'])) {
            $overallRating = (float)$rawAnalysis['overall_rating'];
        } else {
            $overallRating = max(1.0, min(10.0, round($serviceRelevanceScore, 1)));
        }

        // Determine AI Rating Category (good, review_recommended, critical)
        $rawCategory = strtolower((string)($rawAnalysis['ai_rating_category'] ?? $rawAnalysis['overall_ai_rating'] ?? ''));
        if (str_contains($rawCategory, 'critical') || str_contains($rawCategory, '🔴')) {
            $aiRatingCategory = 'critical';
        } elseif (str_contains($rawCategory, 'review') || str_contains($rawCategory, '🟡') || str_contains($rawCategory, 'warning')) {
            $aiRatingCategory = 'review_recommended';
        } elseif (str_contains($rawCategory, 'good') || str_contains($rawCategory, '🟢') || str_contains($rawCategory, 'success')) {
            $aiRatingCategory = 'good';
        } else {
            $aiRatingCategory = 'good';
        }

        $isDropped = str_starts_with($transcript, '[CALL_TOO_SHORT]');

        // Level 4 Critical Policy Violations strictly force Critical category and low score
        if ($isDropped) {
            $aiRatingCategory   = 'review_recommended';
            $overallRating      = 0.0;
            $adminAlertRequired = 0;
            $riskScore          = 0;
            $communication      = 0;
            $professionalism    = 0;
            $empathy            = 0;
            $compliance         = 0;
        } elseif ($offPlatformPayment || $contactSharing || $hasAbuse || ($hasComplaint && $refundRequested) || str_contains($rawCategory, 'critical')) {
            $aiRatingCategory = 'critical';
            $overallRating = min($overallRating, 3.5);
            $adminAlertRequired = 1;
            $riskScore = 9;
            $communication   = $hasAbuse ? 2 : ($hasComplaint ? 5 : (int)($rawAnalysis['communication'] ?? 8));
            $professionalism = ($offPlatformPayment || $contactSharing) ? 2 : ($hasAbuse ? 2 : (int)($rawAnalysis['professionalism'] ?? 8));
            $empathy         = $hasComplaint ? 4 : (int)($rawAnalysis['empathy'] ?? 8);
            $compliance      = ($offPlatformPayment || $contactSharing) ? 1 : (int)($rawAnalysis['compliance'] ?? 9);
        } elseif ($hasComplaint || $refundRequested || $outsideServiceRequest || $unrelatedPct > 15.0 || str_contains($rawCategory, 'review') || $overallRating < 8.0) {
            $aiRatingCategory = 'review_recommended';
            $overallRating = max(4.0, min($overallRating, 6.8));
            $adminAlertRequired = 1;
            $riskScore = 5;
            $communication   = $hasAbuse ? 2 : ($hasComplaint ? 5 : (int)($rawAnalysis['communication'] ?? 8));
            $professionalism = ($offPlatformPayment || $contactSharing) ? 2 : ($hasAbuse ? 2 : (int)($rawAnalysis['professionalism'] ?? 8));
            $empathy         = $hasComplaint ? 4 : (int)($rawAnalysis['empathy'] ?? 8);
            $compliance      = ($offPlatformPayment || $contactSharing) ? 1 : (int)($rawAnalysis['compliance'] ?? 9);
        } else {
            $aiRatingCategory = 'good';
            $overallRating = max(8.0, $overallRating);
            $adminAlertRequired = !empty($rawAnalysis['admin_alert_required']) ? 1 : 0;
            $riskScore = 1;
            $communication   = (int)($rawAnalysis['communication'] ?? 8);
            $professionalism = (int)($rawAnalysis['professionalism'] ?? 8);
            $empathy         = (int)($rawAnalysis['empathy'] ?? 8);
            $compliance      = (int)($rawAnalysis['compliance'] ?? 9);
        }

        // Lists & JSON fields
        $serviceTopics           = is_array($rawAnalysis['service_topics'] ?? null) ? $rawAnalysis['service_topics'] : [];
        $importantStatements     = is_array($rawAnalysis['important_statements'] ?? null) ? $rawAnalysis['important_statements'] : [];
        $unrelatedStatements     = is_array($rawAnalysis['unrelated_statements'] ?? null) ? $rawAnalysis['unrelated_statements'] : [];
        $complaintStatements     = is_array($rawAnalysis['complaint_statements'] ?? null) ? $rawAnalysis['complaint_statements'] : [];
        $policyViolations        = is_array($rawAnalysis['policy_violations'] ?? null) ? $rawAnalysis['policy_violations'] : [];
        $alerts                  = is_array($rawAnalysis['alerts'] ?? null) ? $rawAnalysis['alerts'] : [];
        $highlightedConversations= is_array($rawAnalysis['highlighted_conversations'] ?? null) ? $rawAnalysis['highlighted_conversations'] : [];
        $conversationSegments    = is_array($rawAnalysis['conversation_segments'] ?? null) ? $rawAnalysis['conversation_segments'] : [];
        $aiRecommendations       = is_array($rawAnalysis['ai_recommendations'] ?? null) ? $rawAnalysis['ai_recommendations'] : ($rawAnalysis['recommendations'] ?? []);
        $strengths               = is_array($rawAnalysis['strengths'] ?? null) ? $rawAnalysis['strengths'] : [];
        $weaknesses              = is_array($rawAnalysis['weaknesses'] ?? null) ? $rawAnalysis['weaknesses'] : [];

        $tags = ['Client-SP Call', ucfirst($serviceType), ucfirst($aiRatingCategory)];
        if ($offPlatformPayment) $tags[] = 'Off-Platform Payment';
        if ($contactSharing) $tags[] = 'Contact Sharing';
        if ($hasComplaint) $tags[] = 'Complaint';
        if ($refundRequested) $tags[] = 'Refund Request';

        // Extract keywords
        $keywords = $this->extractKeywordsFromText($transcript . ' ' . $summary);

        return [
            'analysis' => [
                'service_type'              => $serviceType,
                'transcript'                => $transcript,
                'summary'                   => $summary,
                'overall_rating'            => min(10.0, max(0.0, $overallRating)),
                'service_relevance_score'   => min(10.0, max(0.0, $serviceRelevanceScore)),
                'service_related_pct'       => min(100.0, max(0.0, $serviceRelatedPct)),
                'unrelated_pct'             => min(100.0, max(0.0, $unrelatedPct)),
                'communication'             => $communication,
                'professionalism'           => $professionalism,
                'empathy'                   => $empathy,
                'compliance'                => $compliance,
                'customer_sentiment'        => $customerSentiment,
                'provider_sentiment'        => $providerSentiment,
                'resolution'                => $hasComplaint ? 'Follow-up Required' : 'Resolved',
                'risk_score'                => $riskScore,
                'ai_rating_category'        => $aiRatingCategory,
                'off_platform_payment'      => $offPlatformPayment,
                'contact_sharing'           => $contactSharing,
                'outside_service_request'   => $outsideServiceRequest,
                'refund_requested'          => $refundRequested,
                'has_complaint'             => $hasComplaint,
                'has_abuse'                 => $hasAbuse,
                'admin_alert_required'      => $adminAlertRequired,
                'admin_review_status'       => 'pending',
                'strengths'                 => json_encode($strengths),
                'weaknesses'                => json_encode($weaknesses),
                'service_topics'            => json_encode($serviceTopics),
                'important_statements'      => json_encode($importantStatements),
                'unrelated_statements'      => json_encode($unrelatedStatements),
                'complaint_statements'      => json_encode($complaintStatements),
                'policy_violations'         => json_encode($policyViolations),
                'highlighted_conversations' => json_encode($highlightedConversations),
                'conversation_segments'     => json_encode($conversationSegments),
                'alerts'                    => json_encode($alerts),
                'recommendations'           => json_encode($aiRecommendations),
                'tags'                      => json_encode($tags),
            ],
            'keywords'       => $keywords,
            'audio_duration' => $rawAnalysis['audio_duration'] ?? 0,
        ];
    }

    /**
     * Keyword extractor for client calls.
     */
    protected function extractKeywordsFromText(string $text): array
    {
        $text = strtolower(strip_tags($text));
        $words = preg_split('/\W+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        $stopWords = [
            'the', 'be', 'to', 'of', 'and', 'a', 'in', 'that', 'have', 'i', 'it', 'for',
            'not', 'on', 'with', 'he', 'as', 'you', 'do', 'at', 'this', 'but', 'his', 'by',
            'from', 'they', 'we', 'say', 'her', 'she', 'or', 'an', 'will', 'my', 'one',
            'all', 'would', 'there', 'their', 'what', 'so', 'up', 'out', 'if', 'about',
            'who', 'get', 'which', 'go', 'me', 'when', 'make', 'can', 'like', 'time', 'no',
            'just', 'him', 'know', 'take', 'people', 'into', 'year', 'your', 'good', 'some',
            'could', 'them', 'see', 'other', 'than', 'then', 'now', 'look', 'only', 'come',
            'its', 'over', 'think', 'also', 'back', 'after', 'use', 'two', 'how', 'our',
            'work', 'first', 'well', 'way', 'even', 'new', 'want', 'because', 'any', 'these',
            'give', 'day', 'most', 'us', 'is', 'are', 'was', 'were', 'been', 'being', 'am',
            'petsfolio', 'hello', 'okay', 'sir', 'yes', 'yeah', 'please', 'thanks', 'thank'
        ];

        $wordCounts = [];
        foreach ($words as $word) {
            if (strlen($word) < 4 || in_array($word, $stopWords)) {
                continue;
            }
            $wordCounts[$word] = ($wordCounts[$word] ?? 0) + 1;
        }

        arsort($wordCounts);
        return array_slice($wordCounts, 0, 10, true);
    }
}
