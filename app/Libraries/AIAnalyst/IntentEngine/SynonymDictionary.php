<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * SynonymDictionary
 * 
 * Normalizes user vocabulary to canonical terms.
 * Maps colloquial, abbreviated, and misspelled terms to their standard business equivalents.
 * 
 * This is the FIRST layer — it runs before entity extraction and intent classification
 * so all downstream components work with clean, standardized vocabulary.
 */
class SynonymDictionary
{
    /**
     * Entity-type synonyms: map user terms → canonical entity values.
     */
    private const ENTITY_SYNONYMS = [
        // Service types
        'dog walking'    => 'walking',
        'walk'           => 'walking',
        'walks'          => 'walking',
        'walker'         => 'walking',
        'walkers'        => 'walking',
        'pet walking'    => 'walking',
        'groom'          => 'grooming',
        'groomer'        => 'grooming',
        'groomers'       => 'grooming',
        'bath'           => 'grooming',
        'bathing'        => 'grooming',
        'pet grooming'   => 'grooming',
        'train'          => 'training',
        'trainer'        => 'training',
        'trainers'       => 'training',
        'pet training'   => 'training',
        'sit'            => 'sitting',
        'sitter'         => 'sitting',
        'sitters'        => 'sitting',
        'pet sitting'    => 'sitting',
        'board'          => 'boarding',
        'boarder'        => 'boarding',
        'boarders'       => 'boarding',
        'pet boarding'   => 'boarding',

        // Provider role terms → canonical "provider"
        'service provider'  => 'provider',
        'service providers' => 'provider',
        'sp'                => 'provider',
        'sps'               => 'provider',
        'partner'           => 'provider',
        'partners'          => 'provider',

        // Customer role terms → canonical "customer"
        'client'       => 'customer',
        'clients'      => 'customer',
        'pet parent'   => 'customer',
        'pet parents'  => 'customer',
        'pet owner'    => 'customer',
        'pet owners'   => 'customer',
        'member'       => 'customer',
        'members'      => 'customer',
        'user'         => 'customer',
        'users'        => 'customer',
        'customer'     => 'customer',
        'customers'    => 'customer',

        // City aliases
        'hyd'          => 'hyderabad',
        'secunderabad' => 'hyderabad',
        'secundrabad'  => 'hyderabad',
        'blr'          => 'bengaluru',
        'bangalore'    => 'bengaluru',
        'bombay'       => 'mumbai',
        'madras'       => 'chennai',
        'calcutta'     => 'kolkata',
        'ncr'          => 'delhi',
        'gurgaon'      => 'delhi',
        'gurugram'     => 'delhi',
        'noida'        => 'delhi',

        // Date range aliases
        'last 30 days'   => '30_days',
        'past month'     => '30_days',
        'last 1 month'   => '30_days',
        'past 1 month'   => '30_days',
        'last 7 days'    => '7_days',
        'past 7 days'    => '7_days',
        'last week'      => '7_days',
        'past week'      => '7_days',
        'till today'     => 'all_time',
        'till now'       => 'all_time',
        'till date'      => 'all_time',
        'until today'    => 'all_time',
        'until now'      => 'all_time',
        'to date'        => 'all_time',
        'so far'         => 'all_time',
        'up to now'      => 'all_time',
        'up till now'    => 'all_time',
        'as of today'    => 'all_time',
        'as of now'      => 'all_time',
        'all time'       => 'all_time',
        'overall'        => 'all_time',
        'lifetime'       => 'all_time',
        'from beginning' => 'all_time',
        'from starting'  => 'all_time',
        'since inception' => 'all_time',

        // Status aliases
        'approved'       => 'active',
        'suspended'      => 'inactive',
        'non verified'   => 'non_verified',
        'unverified'     => 'non_verified',
        'old sp'         => 'non_verified',
        'old non verified' => 'non_verified',
        'fully verified' => 'verified',
        'active verified' => 'verified',
        'pending kyc'    => 'pending_kyc',
        'kyc pending'    => 'pending_kyc',
    ];

    /**
     * Intent-signal synonyms: map colloquial phrases → canonical intent-signal terms.
     * These help the IntentClassifier detect intents from natural language.
     */
    private const INTENT_SYNONYMS = [
        // Booking/Job terms
        'orders'            => 'bookings',
        'order'             => 'booking',
        'service requests'  => 'bookings',
        'service request'   => 'booking',
        'jobs'              => 'bookings',
        'job'               => 'booking',
        'requests'          => 'bookings',
        'request'           => 'booking',

        // Financial terms
        'revenue'           => 'revenue',
        'income'            => 'revenue',
        'sales'             => 'revenue',
        'commission'        => 'platform_fee',
        'platform commission' => 'platform_fee',
        'petsfolio commission' => 'platform_fee',
        'cashfree'          => 'payout',
        'withdrawal'        => 'payout',
        'withdrawals'       => 'payout',
        'withdrawled'       => 'withdrawn',
        'withdrawl'         => 'withdrawal',
        'withdrawls'        => 'withdrawals',

        // Reconciliation terms
        'recoinciliation'   => 'reconciliation',
        'reconcile'         => 'reconciliation',
        'reconciling'       => 'reconciliation',
        'wallet audit'      => 'reconciliation',
        'wallet check'      => 'reconciliation',
        'wallet mismatch'   => 'reconciliation',
        'wallet discrepancy' => 'reconciliation',
        'ledger'            => 'reconciliation',

        // Verification terms
        'telephonic interview' => 'verification_call',
        'telephonic interviews' => 'verification_calls',
        'executive call'    => 'verification_call',
        'executive calls'   => 'verification_calls',
        'verifier call'     => 'verification_call',
        'verifier calls'    => 'verification_calls',
        'sp verification'   => 'verification_call',

        // Off-platform / quality terms
        'contact sharing'   => 'off_platform',
        'outside platform'  => 'off_platform',
        'bypass platform'   => 'off_platform',
        'call qa'           => 'call_quality',
        'qa score'          => 'call_quality',
        'abusive calls'     => 'call_quality',
        'call sentiment'    => 'call_quality',

        // Funnel terms
        'bottleneck'        => 'funnel',
        'drop off'          => 'dropoff',
        'drop offs'         => 'dropoffs',
        'drop-off'          => 'dropoff',
        'drop-offs'         => 'dropoffs',
        'halt reasons'      => 'dropoff_reasons',
        'cancellation reasons' => 'dropoff_reasons',

        // Notification terms
        'push notification'  => 'notification',
        'push notifications' => 'notifications',
        'fcm'               => 'notification',
        'fcm token'         => 'notification',
        'fcm tokens'        => 'notifications',

        // Loyalty terms
        'reward points'     => 'loyalty',
        'rewards'           => 'loyalty',
        'spin wheel'        => 'loyalty',
        'loyalty wallet'    => 'loyalty',
        'loyalty points'    => 'loyalty',

        // Tracking terms
        'not tracked'       => 'untracked',
        'without tracking'  => 'untracked',
        'missed tracking'   => 'untracked',
        'short walk'        => 'suspicious_tracking',
        'suspicious walk'   => 'suspicious_tracking',
    ];

    /**
     * Common typo corrections.
     */
    private const TYPO_CORRECTIONS = [
        'recoinciliation' => 'reconciliation',
        'reconsiliation'  => 'reconciliation',
        'reconcilaton'    => 'reconciliation',
        'reconiliation'   => 'reconciliation',
        'provder'         => 'provider',
        'proviers'        => 'providers',
        'earings'         => 'earnings',
        'earnigs'         => 'earnings',
        'wallker'         => 'walker',
        'walkker'         => 'walker',
        'grommer'         => 'groomer',
        'groommer'        => 'groomer',
        'tainer'          => 'trainer',
        'custmer'         => 'customer',
        'customar'        => 'customer',
        'notifcation'     => 'notification',
        'notificaton'     => 'notification',
        'boking'          => 'booking',
        'booknig'         => 'booking',
        'verfication'     => 'verification',
        'verificaton'     => 'verification',
        'withdrawl'       => 'withdrawal',
        'withdrawel'      => 'withdrawal',
        'payot'           => 'payout',
        'payuot'          => 'payout',
        'comunity'        => 'community',
        'comminity'       => 'community',
        'loyality'        => 'loyalty',
        'loyalti'         => 'loyalty',
        'hydrabad'        => 'hyderabad',
        'hyderbad'        => 'hyderabad',
        'banglore'        => 'bangalore',
        'bangalor'        => 'bangalore',
        'chenni'          => 'chennai',
        'chenai'          => 'chennai',
    ];

    /**
     * Filler/stopword phrases to strip before processing.
     */
    private const FILLER_PHRASES = [
        'i want to know about',
        'i want to know',
        'can you tell me about',
        'can you tell me',
        'can you show me',
        'can you give me',
        'please check',
        'please tell me',
        'please show me',
        'please find',
        'please get',
        'let me know about',
        'let me know',
        'tell me about',
        'show me',
        'what is the',
        'what are the',
        'details of',
        'details for',
        'details about',
        'profile of',
        'profile for',
        'profile about',
        'who is',
        'clearly said',
        'i want to',
    ];

    /**
     * Fix common typos in the input string.
     */
    public static function fixTypos(string $text): string
    {
        $words = explode(' ', $text);
        $corrected = [];
        foreach ($words as $word) {
            $lower = strtolower($word);
            $corrected[] = self::TYPO_CORRECTIONS[$lower] ?? $word;
        }
        return implode(' ', $corrected);
    }

    /**
     * Normalize entity terms in the input string.
     * Returns the input with entity synonyms replaced by their canonical form.
     */
    public static function normalizeEntities(string $text): string
    {
        $result = $text;
        // Sort by key length descending so longer phrases match first
        $sorted = self::ENTITY_SYNONYMS;
        uksort($sorted, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($sorted as $synonym => $canonical) {
            $pattern = '/\b' . preg_quote($synonym, '/') . '\b/i';
            $result = preg_replace($pattern, $canonical, $result);
        }
        return $result;
    }

    /**
     * Normalize intent-signal terms in the input string.
     */
    public static function normalizeIntentSignals(string $text): string
    {
        $result = $text;
        $sorted = self::INTENT_SYNONYMS;
        uksort($sorted, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($sorted as $synonym => $canonical) {
            $pattern = '/\b' . preg_quote($synonym, '/') . '\b/i';
            $result = preg_replace($pattern, $canonical, $result);
        }
        return $result;
    }

    /**
     * Strip filler phrases from the input.
     */
    public static function stripFillerPhrases(string $text): string
    {
        $result = $text;
        // Sort by length descending
        $sorted = self::FILLER_PHRASES;
        usort($sorted, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($sorted as $phrase) {
            $pattern = '/\b' . preg_quote($phrase, '/') . '\b/i';
            $result = preg_replace($pattern, ' ', $result);
        }
        return trim(preg_replace('/\s+/', ' ', $result));
    }

    /**
     * Look up the canonical entity value for a given term.
     * Returns null if not found.
     */
    public static function resolveEntity(string $term): ?string
    {
        $lower = strtolower(trim($term));
        return self::ENTITY_SYNONYMS[$lower] ?? null;
    }

    /**
     * Look up the canonical intent signal for a given term.
     */
    public static function resolveIntentSignal(string $term): ?string
    {
        $lower = strtolower(trim($term));
        return self::INTENT_SYNONYMS[$lower] ?? null;
    }

    /**
     * Get all canonical service types.
     */
    public static function getCanonicalServices(): array
    {
        return ['walking', 'grooming', 'training', 'sitting', 'boarding'];
    }

    /**
     * Get all canonical cities.
     */
    public static function getCanonicalCities(): array
    {
        return ['hyderabad', 'bengaluru', 'mumbai', 'chennai', 'kolkata', 'delhi', 'pune'];
    }

    /**
     * Get all canonical provider statuses.
     */
    public static function getCanonicalStatuses(): array
    {
        return ['active', 'inactive', 'verified', 'non_verified', 'pending_kyc', 'approval', 'pending_registration', 'waiting for call'];
    }
}
