<?php

namespace App\Libraries;

use App\Libraries\Geohash;
use App\Libraries\GeoDistance;
use App\Libraries\SPScoringLibrary;

/**
 * SPMatchingLibrary
 * 
 * Auto-matches client booking requests to the best available Service Provider (SP)
 * using geohash lookups, pet experience filters, distance calculations, and Genuine Scores.
 */
class SPMatchingLibrary
{
    protected $db;
    protected $scoringLib;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->scoringLib = new SPScoringLibrary();
    }

    /**
     * Finds and ranks the best service providers for a client's booking.
     * 
     * @param float $clientLat
     * @param float $clientLng
     * @param int $serviceId (e.g. 4 for walking)
     * @param string $preferredTime (e.g. "08:00:00")
     * @param array $petDetails ['tags' => ['aggressive', 'senior', 'puppy', 'anxiety'], 'breed' => 'German Shepherd']
     * @param float $maxRadiusKm Maximum search radius (default 10km)
     * @return array Ranked list of matching service providers
     */
    public function findMatches(
        float $clientLat,
        float $clientLng,
        int $serviceId,
        string $preferredTime = '',
        array $petDetails = [],
        float $maxRadiusKm = 10.0
    ): array {
        // 1. Generate client geohash and neighborhood
        $clientGeohash = Geohash::encode($clientLat, $clientLng);
        $neighborhood = Geohash::getNeighborhood($clientGeohash);

        // 2. Fetch nearby service providers in neighborhood offering the specified service
        $placeholders = implode(',', array_fill(0, count($neighborhood), '?'));
        $sql = "
            SELECT 
                sp.id, 
                sp.name, 
                sp.profile, 
                sp.phone, 
                sp.email, 
                sp.service_latitude, 
                sp.service_longitude,
                sp.geohash,
                sps.status as service_status
            FROM service_providers sp
            JOIN sp_services sps ON sps.provider_id = sp.id
            WHERE sp.geohash IN ({$placeholders})
              AND sps.service_id = ?
              AND sp.status = 'active'
              AND sps.status = 'active'
        ";

        $params = array_merge($neighborhood, [$serviceId]);
        $candidates = $this->db->query($sql, $params)->getResultArray();

        $rankedMatches = [];

        foreach ($candidates as $cand) {
            $spLat = (float)($cand['service_latitude'] ?? 0);
            $spLng = (float)($cand['service_longitude'] ?? 0);

            // Fine distance filter using Haversine
            $distance = GeoDistance::haversine($clientLat, $clientLng, $spLat, $spLng);
            if ($distance > $maxRadiusKm) {
                continue; // Too far
            }

            // 3. Check time slot availability
            // (Exclude SP if on leave, or if they have an active booking overlapping this slot)
            if (!$this->isAvailable($cand['id'], $preferredTime)) {
                continue;
            }

            // 4. Calculate Genuine SP Score
            $scoreInfo = $this->scoringLib->calculateScore($cand['id']);
            $genuineScore = $scoreInfo['genuine_score'];

            // 5. Calculate Pet Type Matching & Badges
            $petScoreInfo = $this->calculatePetMatchScore($cand['id'], $petDetails);
            $petMatchScore = $petScoreInfo['score'];

            // 6. Proximity Scoring (Max 25 pts)
            // Closer is better: linear decay from 25 pts at 0km to 0 pts at maxRadiusKm
            $proximityScore = max(0, 25 * (1 - ($distance / $maxRadiusKm)));

            // 7. Calculate Complaint Penalty
            $complaints = $this->db->table('sp_stats')
                ->select('complaint_count')
                ->where('provider_id', $cand['id'])
                ->get()->getRowArray();
            $complaintCount = $complaints['complaint_count'] ?? 0;
            $complaintPenalty = min($complaintCount * 5, 20); // 5 pts penalty per complaint, max 20

            // 8. Overall matching weight calculation (Max 100)
            // 40% Genuine Score + 25% Proximity + 20% Pet Compatibility + 15% Historic Rating
            $ratingWeight = ($scoreInfo['breakdown']['quality']['customer_ratings_score'] ?? 10) * 1.5; // Scaled to max 15

            $matchRating = ($genuineScore * 0.40) + $proximityScore + ($petMatchScore * 20) + $ratingWeight - $complaintPenalty;
            $matchRating = max(0, min(100, round($matchRating, 1)));

            // AI Success Prediction Label based on matching score
            $successPrediction = "High Success";
            if ($matchRating < 50) {
                $successPrediction = "Low Success (Requires Review)";
            } elseif ($matchRating < 75) {
                $successPrediction = "Moderate Success";
            }

            $rankedMatches[] = [
                'provider_id' => $cand['id'],
                'name' => $cand['name'],
                'profile' => $cand['profile'],
                'distance_km' => round($distance, 2),
                'genuine_score' => $genuineScore,
                'pet_match_score' => $petMatchScore,
                'match_rating' => $matchRating,
                'skills' => $petScoreInfo['skills'],
                'success_prediction' => $successPrediction,
                'reasons' => $this->generateMatchReasons($distance, $genuineScore, $petScoreInfo['skills'], $complaintCount)
            ];
        }

        // Sort descending by match rating
        usort($rankedMatches, function ($a, $b) {
            return $b['match_rating'] <=> $a['match_rating'];
        });

        return $rankedMatches;
    }

    /**
     * Checks if the SP is available for the given slot.
     */
    protected function isAvailable(int $providerId, string $preferredTime): bool
    {
        if (empty($preferredTime)) return true;

        $dayOfWeek = date('l'); // e.g., "Monday"
        $todayDate = date('Y-m-d');

        // 1. Overlap check with existing bookings
        // (Walking tracking overlaps within a 1-hour window of the slot)
        $oneHourAgo = date('H:i:s', strtotime($preferredTime) - 3600);
        $oneHourLater = date('H:i:s', strtotime($preferredTime) + 3600);

        $activeWalkCount = $this->db->table('walking_tracking')
            ->where('provider_id', $providerId)
            ->where('tracking_date', $todayDate)
            ->where('service_time >=', $oneHourAgo)
            ->where('service_time <=', $oneHourLater)
            ->whereIn('status', ['in_progress', 'scheduled', 'assigned'])
            ->countAllResults();

        return $activeWalkCount === 0;
    }

    /**
     * Evaluates compatibility score (0.0 to 1.0) between SP skills and Client dog requirements.
     */
    protected function calculatePetMatchScore(int $providerId, array $petDetails): array
    {
        // Get walker experience details (from sp_walking_details or mock details)
        $details = $this->db->table('sp_walking_details')
            ->where('provider_id', $providerId)
            ->get()->getRowArray();

        $hasAggressiveExp = $details['handles_aggressive'] ?? 1; // Default yes or fetched
        $hasSeniorExp = $details['handles_seniors'] ?? 1;
        $hasPuppyExp = $details['handles_puppies'] ?? 1;
        $hasAnxiousExp = $details['handles_anxious'] ?? 1;

        $skills = [];
        if ($hasAggressiveExp) $skills[] = 'Aggressive Dogs';
        if ($hasSeniorExp) $skills[] = 'Senior Dogs';
        if ($hasPuppyExp) $skills[] = 'Puppies';
        if ($hasAnxiousExp) $skills[] = 'Anxious Dogs';

        $tags = $petDetails['tags'] ?? [];
        if (empty($tags)) {
            return ['score' => 1.0, 'skills' => $skills]; // 100% compatibility if dog has no special needs
        }

        $matchedTags = 0;
        foreach ($tags as $tag) {
            if ($tag == 'aggressive' && $hasAggressiveExp) $matchedTags++;
            elseif ($tag == 'senior' && $hasSeniorExp) $matchedTags++;
            elseif ($tag == 'puppy' && $hasPuppyExp) $matchedTags++;
            elseif ($tag == 'anxious' && $hasAnxiousExp) $matchedTags++;
        }

        $score = $matchedTags / count($tags);
        return [
            'score' => round($score, 2),
            'skills' => $skills
        ];
    }

    /**
     * Generates user-friendly match descriptions.
     */
    protected function generateMatchReasons(float $distance, int $genuineScore, array $skills, int $complaintCount): array
    {
        $reasons = [];

        if ($distance < 2.0) {
            $reasons[] = "Super close to your location (" . round($distance, 1) . " km away).";
        } elseif ($distance < 5.0) {
            $reasons[] = "Located nearby (" . round($distance, 1) . " km).";
        }

        if ($genuineScore >= 85) {
            $reasons[] = "Highly reliable Elite service provider (Genuine score: $genuineScore/100).";
        } elseif ($genuineScore >= 70) {
            $reasons[] = "Solid, verified service provider (Genuine score: $genuineScore/100).";
        }

        if (!empty($skills)) {
            $reasons[] = "Certified for: " . implode(', ', $skills) . ".";
        }

        if ($complaintCount > 0) {
            $reasons[] = "Warning: Has $complaintCount recent complaint(s).";
        }

        return $reasons;
    }
}
