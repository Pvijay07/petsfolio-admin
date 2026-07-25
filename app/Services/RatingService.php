<?php

namespace App\Services;

class RatingService
{
    /**
     * Normalize raw AI analysis JSON into business structure ready for DB persistence.
     *
     * @param array $rawAnalysis Output from AIService
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
        $communication   = isset($rawAnalysis['communication']) ? (int)$rawAnalysis['communication'] : 8;
        $professionalism = isset($rawAnalysis['professionalism']) ? (int)$rawAnalysis['professionalism'] : 9;
        $empathy         = isset($rawAnalysis['empathy']) ? (int)$rawAnalysis['empathy'] : 8;
        $compliance      = isset($rawAnalysis['compliance']) ? (int)$rawAnalysis['compliance'] : 9;

        // Overall rating calculation (scale 1 to 10)
        if (isset($rawAnalysis['overall_rating'])) {
            $overallRating = (float)$rawAnalysis['overall_rating'];
        } elseif (isset($rawAnalysis['rating'])) {
            $overallRating = (float)$rawAnalysis['rating'];
        } else {
            $overallRating = round(($communication + $professionalism + $empathy + $compliance) / 4, 1);
        }

        $riskScore = isset($rawAnalysis['risk_score']) ? (int)$rawAnalysis['risk_score'] : 0;

        $strengths       = is_array($rawAnalysis['strengths'] ?? null) ? $rawAnalysis['strengths'] : [];
        $weaknesses      = is_array($rawAnalysis['weaknesses'] ?? null) ? $rawAnalysis['weaknesses'] : [];
        $recommendations = is_array($rawAnalysis['recommendations'] ?? null) ? $rawAnalysis['recommendations'] : [];
        $tags            = is_array($rawAnalysis['tags'] ?? null) ? $rawAnalysis['tags'] : ['General'];

        // Keyword extraction logic
        $keywords = [];
        if (isset($rawAnalysis['keywords']) && is_array($rawAnalysis['keywords'])) {
            $keywords = $rawAnalysis['keywords'];
        } else {
            $keywords = $this->extractKeywordsFromText($transcript . ' ' . $summary);
        }

        return [
            'analysis' => [
                'transcript'         => $transcript,
                'summary'            => $summary,
                'overall_rating'     => min(10.0, max(0.0, $overallRating)),
                'communication'      => min(10, max(0, $communication)),
                'professionalism'    => min(10, max(0, $professionalism)),
                'empathy'            => min(10, max(0, $empathy)),
                'compliance'         => min(10, max(0, $compliance)),
                'customer_sentiment' => ucfirst(strtolower($customerSentiment)),
                'provider_sentiment' => ucfirst(strtolower($providerSentiment)),
                'resolution'         => ucfirst(strtolower($resolution)),
                'risk_score'         => min(10, max(0, $riskScore)),
                'strengths'          => json_encode($strengths),
                'weaknesses'         => json_encode($weaknesses),
                'recommendations'    => json_encode($recommendations),
                'tags'               => json_encode($tags),
            ],
            'keywords' => $keywords
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
