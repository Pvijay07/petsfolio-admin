<?php

namespace App\Services;

class RatingService
{
    /**
     * Normalize raw AI analysis JSON into business structure ready for DB persistence.
     * Includes abuse detection fields, strict scoring enforcement, and audio duration.
     *
     * @param array $rawAnalysis Output from AIService / Python Worker
     * @return array Formatted array matching call_analysis and call_keywords table schema
     */
    public function formatAnalysisPayload(array $rawAnalysis): array
    {
        $transcript        = $rawAnalysis['transcript'] ?? '';
        $summary           = $rawAnalysis['summary'] ?? '';
        $customerSentiment = $rawAnalysis['customer_sentiment'] ?? ($rawAnalysis['sentiment'] ?? 'Neutral');
        $providerSentiment = $rawAnalysis['provider_sentiment'] ?? 'Neutral';
        $resolution        = $rawAnalysis['resolution'] ?? 'Resolved';

        // Criteria scores (0 to 10)
        $communication   = isset($rawAnalysis['communication']) ? (int)$rawAnalysis['communication'] : 5;
        $professionalism = isset($rawAnalysis['professionalism']) ? (int)$rawAnalysis['professionalism'] : 5;
        $empathy         = isset($rawAnalysis['empathy']) ? (int)$rawAnalysis['empathy'] : 5;
        $compliance      = isset($rawAnalysis['compliance']) ? (int)$rawAnalysis['compliance'] : 6;

        // ─── Abuse Detection Fields ─────────────────────────────────
        $hasAbuse = !empty($rawAnalysis['has_abuse']) ? 1 : 0;
        $abuseInstances = [];
        if (isset($rawAnalysis['abuse_instances']) && is_array($rawAnalysis['abuse_instances'])) {
            $abuseInstances = $rawAnalysis['abuse_instances'];
        }
        $negativeSpeechFlags = [];
        if (isset($rawAnalysis['negative_speech_flags']) && is_array($rawAnalysis['negative_speech_flags'])) {
            $negativeSpeechFlags = $rawAnalysis['negative_speech_flags'];
        }

        // ─── Strict Scoring Enforcement ─────────────────────────────
        // If abuse detected, enforce caps on professionalism and overall
        if ($hasAbuse) {
            $professionalism = min($professionalism, 3);
            $empathy = min($empathy, 4);
        }

        // Overall rating calculation (scale 1 to 10)
        if (isset($rawAnalysis['overall_rating'])) {
            $overallRating = (float)$rawAnalysis['overall_rating'];
        } elseif (isset($rawAnalysis['rating'])) {
            $overallRating = (float)$rawAnalysis['rating'];
        } else {
            $overallRating = round(($communication + $professionalism + $empathy + $compliance) / 4, 1);
        }

        // Cap overall for abusive calls
        if ($hasAbuse) {
            $overallRating = min($overallRating, 4.0);
        }

        $riskScore = isset($rawAnalysis['risk_score']) ? (int)$rawAnalysis['risk_score'] : 0;

        // Increase risk for abuse
        if ($hasAbuse && $riskScore < 5) {
            $riskScore = max($riskScore, 6);
        }

        // Determine verifier performance fields
        $verificationStatus = $rawAnalysis['verification_status'] ?? null;
        if (!$verificationStatus) {
            if ($hasAbuse) {
                $verificationStatus = 'escalated';
            } elseif ($overallRating >= 7.0 && $riskScore <= 3) {
                $verificationStatus = 'approved';
            } elseif ($riskScore >= 4 && $riskScore <= 6) {
                $verificationStatus = 're_verification';
            } elseif ($riskScore >= 7) {
                $verificationStatus = 'escalated';
            } else {
                $verificationStatus = 'pending';
            }
        }

        $qaScore = isset($rawAnalysis['qa_score']) ? (float)$rawAnalysis['qa_score'] : $overallRating;
        if ($hasAbuse) {
            $qaScore = min($qaScore, 3.0);
        }

        $isEscalated = isset($rawAnalysis['is_escalated']) ? (int)$rawAnalysis['is_escalated'] : ($verificationStatus === 'escalated' ? 1 : 0);
        if ($hasAbuse) {
            $isEscalated = 1;
        }

        $hasComplaint = isset($rawAnalysis['has_complaint']) ? (int)$rawAnalysis['has_complaint'] : (strtolower($customerSentiment) === 'negative' && $riskScore >= 6 ? 1 : 0);

        $strengths       = is_array($rawAnalysis['strengths'] ?? null) ? $rawAnalysis['strengths'] : [];
        $weaknesses      = is_array($rawAnalysis['weaknesses'] ?? null) ? $rawAnalysis['weaknesses'] : [];
        $recommendations = is_array($rawAnalysis['recommendations'] ?? null) ? $rawAnalysis['recommendations'] : [];
        $tags            = is_array($rawAnalysis['tags'] ?? null) ? $rawAnalysis['tags'] : ['General'];

        // Add abuse tag if flagged
        if ($hasAbuse && !in_array('Abuse Flagged', $tags)) {
            $tags[] = 'Abuse Flagged';
        }

        // Keyword extraction logic
        $keywords = [];
        if (isset($rawAnalysis['keywords']) && is_array($rawAnalysis['keywords'])) {
            $keywords = $rawAnalysis['keywords'];
        } else {
            $keywords = $this->extractKeywordsFromText($transcript . ' ' . $summary);
        }

        return [
            'analysis' => [
                'transcript'            => $transcript,
                'summary'               => $summary,
                'overall_rating'        => min(10.0, max(0.0, $overallRating)),
                'communication'         => min(10, max(0, $communication)),
                'professionalism'       => min(10, max(0, $professionalism)),
                'empathy'               => min(10, max(0, $empathy)),
                'compliance'            => min(10, max(0, $compliance)),
                'customer_sentiment'    => ucfirst(strtolower($customerSentiment)),
                'provider_sentiment'    => ucfirst(strtolower($providerSentiment)),
                'resolution'            => ucfirst(strtolower($resolution)),
                'verification_status'   => $verificationStatus,
                'qa_score'              => min(10.0, max(0.0, $qaScore)),
                'is_escalated'          => $isEscalated,
                'has_complaint'         => $hasComplaint,
                'has_abuse'             => $hasAbuse,
                'abuse_instances'       => json_encode($abuseInstances),
                'negative_speech_flags' => json_encode($negativeSpeechFlags),
                'risk_score'            => min(10, max(0, $riskScore)),
                'strengths'             => json_encode($strengths),
                'weaknesses'            => json_encode($weaknesses),
                'recommendations'       => json_encode($recommendations),
                'tags'                  => json_encode($tags),
            ],
            'keywords'       => $keywords,
            'audio_duration' => $rawAnalysis['audio_duration'] ?? 0,
        ];
    }

    /**
     * Simple keyword frequency extractor fallback.
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
            'give', 'day', 'most', 'us', 'is', 'are', 'was', 'were', 'been', 'being', 'am'
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
