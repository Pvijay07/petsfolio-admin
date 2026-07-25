<?php

namespace App\Libraries;

/**
 * SPScoringLibrary
 * 
 * Computes the Genuine Service Provider Score (0-100) based on:
 * 1. Verification/Profile complete factors (30%)
 * 2. Behavioral/Attendance factors (40%)
 * 3. Review ratings & Sentiment factors (30%)
 */
class SPScoringLibrary
{
    protected $db;
    protected $providerModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->providerModel = new \App\Models\Provider();
    }

    /**
     * Calculates the overall Genuine Score for a service provider.
     * 
     * @param int $providerId
     * @return array Score breakdown and final score
     */
    public function calculateScore(int $providerId): array
    {
        $verificationScore = $this->getVerificationScore($providerId);
        $behaviorScore = $this->getBehaviorScore($providerId);
        $qualityScore = $this->getQualityScore($providerId);

        $totalScore = round($verificationScore['total'] + $behaviorScore['total'] + $qualityScore['total']);
        $totalScore = max(0, min(100, $totalScore));

        return [
            'provider_id' => $providerId,
            'genuine_score' => $totalScore,
            'breakdown' => [
                'verification' => $verificationScore,
                'behavior' => $behaviorScore,
                'quality' => $qualityScore
            ],
            'evaluated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Profile completeness & verification checklist scoring (Max 30 pts)
     */
    protected function getVerificationScore(int $providerId): array
    {
        $score = 0;
        
        // 1. Fetch provider details
        $provider = $this->db->table('service_providers')
            ->select('profile, experience_years, status')
            ->where('id', $providerId)
            ->get()->getRowArray();

        // 2. Fetch verification status
        $verification = $this->db->table('sp_verifications')
            ->where('provider_id', $providerId)
            ->get()->getRowArray();

        // 3. Fetch certifications/details
        $details = $this->db->table('sp_walking_details')
            ->where('provider_id', $providerId)
            ->get()->getRowArray();

        $profileComplete = (!empty($provider['profile']) && !empty($provider['experience_years'])) ? 5 : 2;
        $kycVerified = ($verification['kyc_verified'] ?? 0) == 1 ? 5 : 0;
        $policeVerified = ($verification['police_verification_verified'] ?? 0) == 1 ? 10 : 0;
        $experienceProof = ($provider['experience_years'] ?? 0) >= 1 ? 5 : 2;
        $certifications = (!empty($details['has_certifications']) || ($details['is_accidentally_insured'] ?? 0) == 1) ? 5 : 0;

        $total = $profileComplete + $kycVerified + $policeVerified + $experienceProof + $certifications;

        return [
            'total' => $total,
            'profile_completeness' => $profileComplete,
            'kyc_verification' => $kycVerified,
            'police_verification' => $policeVerified,
            'experience_proof' => $experienceProof,
            'certifications' => $certifications
        ];
    }

    /**
     * Behavioral & App interaction scoring (Max 40 pts)
     */
    protected function getBehaviorScore(int $providerId): array
    {
        // 1. Login/Attendance behavior (Logins/Activity in last 30 days)
        // Check active days based on tracking entries or messages sent in last 30 days
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        $walkDays = $this->db->table('walking_tracking')
            ->select('tracking_date as date')
            ->where('provider_id', $providerId)
            ->where('tracking_date >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('tracking_date')
            ->getCompiledSelect();
            
        $messageDays = $this->db->table('booking_messages')
            ->select('DATE(created_at) as date')
            ->where('sender_type', 'service_provider')
            ->where('sender_id', $providerId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->groupBy('DATE(created_at)')
            ->getCompiledSelect();
            
        $activityQuery = $this->db->query("
            SELECT COUNT(DISTINCT date) as active_days FROM (
                ($walkDays) UNION ($messageDays)
            ) as combined_activity
        ");
        
        $activeDays = $activityQuery->getRow()->active_days ?? 0;
        $attendance = min(5, round(($activeDays / 10) * 5, 1));

        // 2. Response time: Check booking conversation messages
        // Calculate average difference between client message and SP reply in the same conversation
        $responseQuery = $this->db->query("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, client_msg.created_at, sp_msg.created_at)) as avg_response_time
            FROM booking_messages sp_msg
            JOIN booking_messages client_msg ON client_msg.conversation_id = sp_msg.conversation_id
            WHERE sp_msg.sender_type = 'service_provider'
              AND sp_msg.sender_id = ?
              AND client_msg.sender_type = 'client'
              AND client_msg.created_at < sp_msg.created_at
              AND NOT EXISTS (
                  SELECT 1 FROM booking_messages sub
                  WHERE sub.conversation_id = sp_msg.conversation_id
                    AND sub.created_at > client_msg.created_at
                    AND sub.created_at < sp_msg.created_at
              )
        ", [$providerId]);
        
        $avgMinutes = $responseQuery->getRow()->avg_response_time;
        if ($avgMinutes === null) {
            $responseTime = 5.0; // Default/neutral score if no message interactions
        } elseif ($avgMinutes <= 10) {
            $responseTime = 5.0;
        } elseif ($avgMinutes <= 30) {
            $responseTime = 3.0;
        } elseif ($avgMinutes <= 60) {
            $responseTime = 1.0;
        } else {
            $responseTime = 0.0;
        }

        // 3. Bid acceptance vs rejection (quotations table)
        $quotes = $this->db->table('quotations')
            ->select('status')
            ->where('provider_id', $providerId)
            ->get()->getResultArray();

        $totalBids = count($quotes);
        $acceptedBids = 0;
        foreach ($quotes as $q) {
            if (in_array(strtolower($q['status']), ['accepted', 'completed', 'confirmed'])) {
                $acceptedBids++;
            }
        }
        $bidAcceptanceRate = $totalBids > 0 ? ($acceptedBids / $totalBids) * 100 : 80; // default 80% if no bids
        $bidScore = ($bidAcceptanceRate / 100) * 10;

        // 4. Job Completion Rate (walking_tracking completed vs total)
        $walks = $this->db->table('walking_tracking')
            ->select('status')
            ->where('provider_id', $providerId)
            ->get()->getResultArray();

        $totalWalks = count($walks);
        $completedWalks = 0;
        $cancelledWalks = 0;
        foreach ($walks as $w) {
            if ($w['status'] == 'completed') $completedWalks++;
            if ($w['status'] == 'cancelled') $cancelledWalks++;
        }
        
        $completionRate = $totalWalks > 0 ? ($completedWalks / $totalWalks) * 100 : 90; // default 90%
        $cancellationRate = $totalWalks > 0 ? ($cancelledWalks / $totalWalks) * 100 : 0;

        $completionScore = ($completionRate / 100) * 10;
        
        // 5. On-Time Arrival Score (using the real on-time arrival rate metrics from the Provider model)
        $onTimeData = $this->providerModel->onTimeAtService($providerId);
        $totalOnTimeCount = $onTimeData['overall']['total'] ?? 0;
        if ($totalOnTimeCount > 0) {
            $onTimePercentage = $onTimeData['overall']['on_time_percentage'] ?? 100;
            $onTimeScore = round(($onTimePercentage / 100) * 5, 1);
        } else {
            $onTimeScore = 5.0; // Default/neutral score for SPs with no bookings
        }
        
        // Cancellation penalty (deducts from maximum cancellation score of 5)
        $cancellationScore = max(0, 5 - ($cancellationRate / 5));

        $total = $attendance + $responseTime + $bidScore + $completionScore + $onTimeScore + $cancellationScore;

        return [
            'total' => round($total, 1),
            'app_opening_frequency' => $attendance,
            'response_time_score' => $responseTime,
            'bid_acceptance_score' => round($bidScore, 1),
            'job_completion_score' => round($completionScore, 1),
            'on_time_arrival' => $onTimeScore,
            'cancellation_score' => round($cancellationScore, 1)
        ];
    }

    /**
     * Quality, Ratings, and Reviews Sentiment (Max 30 pts)
     */
    protected function getQualityScore(int $providerId): array
    {
        // 1. Average rating (sp_reviews or feedbacks)
        $ratingData = $this->db->table('sp_reviews')
            ->select('AVG(rating) as avg_rating, COUNT(id) as total')
            ->where('provider_id', $providerId)
            ->get()->getRowArray();
        
        $avgRating = $ratingData['avg_rating'] ?? 4.0;
        $ratingScore = ($avgRating / 5) * 15; // Max 15 pts

        // 2. Repeat clients
        $repeatQuery = $this->db->query("
            SELECT COUNT(DISTINCT user_id) as repeats FROM (
                SELECT user_id FROM walking_service_bookings wsb
                JOIN quotations q ON q.booking_id = wsb.id
                WHERE q.provider_id = ? AND wsb.status = 'Completed'
                GROUP BY user_id HAVING COUNT(wsb.id) > 1
            ) as t
        ", [$providerId]);
        $repeatClientsCount = $repeatQuery->getRow()->repeats ?? 0;
        $repeatScore = min($repeatClientsCount * 1.5, 5); // Max 5 pts

        // 3. Sentiment Analysis (NLP logic stub) (Max 10 pts)
        // Fetches last 5 review comments, performs simple keyword-based sentiment mapping
        $reviews = $this->db->table('sp_reviews')
            ->select('review')
            ->where('provider_id', $providerId)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $sentimentScore = 8; // Default good sentiment if no reviews
        if (!empty($reviews)) {
            $totalSentiment = 0;
            $counted = 0;
            foreach ($reviews as $rev) {
                if (empty($rev['review'])) continue;
                $sentiment = $this->analyzeSentiment($rev['review']);
                $totalSentiment += $sentiment;
                $counted++;
            }
            if ($counted > 0) {
                $sentimentScore = ($totalSentiment / $counted) * 10;
            }
        }

        $total = $ratingScore + $repeatScore + $sentimentScore;

        return [
            'total' => round($total, 1),
            'customer_ratings_score' => round($ratingScore, 1),
            'repeat_clients_score' => round($repeatScore, 1),
            'review_sentiment_score' => round($sentimentScore, 1)
        ];
    }

    /**
     * Mock AI Sentiment Analysis Engine
     * Evaluates a string and returns a sentiment score between 0.0 (very negative) and 1.0 (very positive)
     */
    public function analyzeSentiment(string $text): float
    {
        $text = strtolower($text);

        // Keywords for mapping
        $positive = ['great', 'excellent', 'amazing', 'happy', 'good', 'friendly', 'best', 'punctual', 'loved', 'perfect', 'awesome'];
        $negative = ['bad', 'poor', 'rude', 'late', 'missed', 'unprofessional', 'worst', 'angry', 'careless', 'complaint', 'hurt'];

        $posCount = 0;
        $negCount = 0;

        foreach ($positive as $p) {
            if (strpos($text, $p) !== false) $posCount++;
        }
        foreach ($negative as $n) {
            if (strpos($text, $n) !== false) $negCount++;
        }

        if ($posCount === 0 && $negCount === 0) {
            return 0.75; // Neutral-positive bias
        }

        $score = ($posCount - $negCount) / ($posCount + $negCount);
        // Normalize from [-1, 1] to [0.2, 1.0]
        $normalized = (($score + 1) / 2) * 0.8 + 0.2;
        return round($normalized, 2);
    }
}
