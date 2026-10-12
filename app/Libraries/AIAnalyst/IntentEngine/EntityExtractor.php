<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

use App\Libraries\AIAnalyst\BusinessDictionary;

/**
 * EntityExtractor
 * 
 * Stage 2 of the Intent Engine pipeline.
 * Extracts structured entities from both the ORIGINAL and NORMALIZED messages:
 *   - phone: Indian mobile number (10 digits starting with 6-9)
 *   - service_type: walking, grooming, training, sitting, boarding
 *   - city: Canonical city name
 *   - date_range: Temporal scope (today, this_month, all_time, etc.)
 *   - status: Provider/booking status
 *   - booking_status: Active booking filter (active, halted, deleted, etc.)
 *   - search_name: Extracted person name for individual lookups
 *   - role_context: Whether the query is about providers, customers, or ambiguous
 */
class EntityExtractor
{
    /**
     * Extract all entities from the normalized query context.
     * 
     * @param array  $queryContext  Output from QueryNormalizer::normalize()
     * @param string $historyNorm  Normalized history text
     * @return array  Extracted entities keyed by type
     */
    public static function extract(array $queryContext, string $historyNorm = ''): array
    {
        $original   = $queryContext['original'];
        $normalized = $queryContext['normalized'];
        $lowered    = $queryContext['lowered'];

        $entities = [];

        // 1. Phone number (extract from ORIGINAL — preserves digits)
        if (preg_match('/\b([6-9]\d{9})\b/', $original, $m)) {
            $entities['phone'] = $m[1];
        }

        // 1b. Booking ID (extract from ORIGINAL or NORMALIZED: e.g. PSF0ea7eWLK, SF0ea7eWLK)
        if (preg_match('/\b(P?SF[a-zA-Z0-9]+)\b/i', $original, $bm)) {
            $rawBid = strtoupper(trim($bm[1]));
            if (str_starts_with($rawBid, 'SF') && !str_starts_with($rawBid, 'PSF')) {
                $rawBid = 'P' . $rawBid;
            }
            $entities['booking_id'] = $rawBid;
        }

        // 2. Service type
        $svc = self::detectService($normalized);
        if ($svc !== null) {
            $entities['service_type'] = $svc;
        }

        // 3. City
        $city = self::detectCity($normalized);
        if ($city !== null) {
            $entities['city'] = $city;
        }

        // 4. Date range
        $dr = self::detectDateRange($normalized);
        if ($dr !== null) {
            $entities['date_range'] = $dr;
        }
        $drs = self::detectDateRanges($normalized);
        if (!empty($drs)) {
            $entities['date_ranges'] = $drs;
        }

        // 5. Provider status
        $status = self::detectProviderStatus($normalized);
        if ($status !== null) {
            $entities['status'] = $status;
        }

        // 6. Booking status
        $bs = self::detectBookingStatus($normalized);
        if ($bs !== null) {
            $entities['booking_status'] = $bs;
        }

        // 7. Role context (is this about providers or customers?)
        $entities['role_context'] = self::detectRoleContext($normalized);

        // 8. Search name (extracted from ORIGINAL, with entity/filler tokens removed)
        $searchName = self::extractSearchName($original, $normalized);
        if (!empty($searchName)) {
            $entities['search_name'] = $searchName;
        }

        // 9. Try to resolve name from history if not found in current message
        if (empty($entities['search_name']) && !empty($historyNorm)) {
            $histName = self::extractNameFromHistory($historyNorm);
            if (!empty($histName)) {
                $entities['search_name_from_history'] = $histName;
            }
        }

        return $entities;
    }

    /**
     * Detect service type from normalized text.
     */
    public static function detectService(string $text): ?string
    {
        $matches = [];
        if (preg_match('/\b(?:walking|walk|walkers?)\b/i', $text))   $matches[] = 'walking';
        if (preg_match('/\b(?:grooming|groom|groomers?|bath|bathing)\b/i', $text))  $matches[] = 'grooming';
        if (preg_match('/\b(?:training|train|trainers?)\b/i', $text)) $matches[] = 'training';
        if (preg_match('/\b(?:sitting|sit|sitters?)\b/i', $text))    $matches[] = 'sitting';
        if (preg_match('/\b(?:boarding|board|boarders?)\b/i', $text)) $matches[] = 'boarding';

        if (count($matches) > 1) {
            return 'all';
        }
        return $matches[0] ?? null;
    }

    /**
     * Detect city from normalized text.
     */
    public static function detectCity(string $text): ?string
    {
        if (preg_match('/\b(?:hyderabad|hyd|secunderabad)\b/', $text)) return 'Hyderabad';
        if (preg_match('/\b(?:bengaluru|bangalore|blr)\b/', $text))    return 'Bengaluru';
        if (preg_match('/\b(?:mumbai|bombay)\b/', $text))              return 'Mumbai';
        if (preg_match('/\b(?:delhi|ncr|gurgaon|gurugram|noida)\b/', $text)) return 'New Delhi';
        if (preg_match('/\b(?:chennai|madras)\b/', $text))             return 'Chennai';
        if (preg_match('/\bpune\b/', $text))                           return 'Pune';
        if (preg_match('/\bkolkata\b/', $text))                        return 'Kolkata';
        return null;
    }

    /**
     * Detect date range from normalized text.
     */
    public static function detectDateRange(string $text): ?string
    {
        // Specific named month (e.g., "january", "jan 2026")
        if (preg_match('/\b(january|february|march|april|may|june|july|august|september|october|november|december|jan|feb|mar|apr|may|jun|jul|aug|sep|sept|oct|nov|dec)\b(?:[_\s]+month)?[_\s]*(\d{4})?/i', $text, $m)) {
            $mName = $m[1];
            $year  = !empty($m[2]) ? $m[2] : date('Y');
            return "{$mName}_{$year}";
        }

        // "all time" / "till today" variants (must check BEFORE "today")
        if (preg_match('/\b(?:till\s*(?:today|now|date)|until\s*(?:today|now)|to\s*date|so\s*far|up\s*(?:to|till)\s*now|as\s*of\s*(?:today|now)|all[\s_]*time|overall|lifetime|from\s*beginning|from\s*starting|since\s*inception)\b/i', $text)) {
            return 'all_time';
        }

        // Relative ranges
        if (preg_match('/\b(?:last\s*30\s*days|30\s*days|past\s*month|last\s*1\s*month|past\s*1\s*month)\b/i', $text)) return '30_days';
        if (preg_match('/\b(?:last\s*7\s*days|7\s*days|past\s*7\s*days|last\s*week|past\s*week)\b/i', $text))          return '7_days';
        if (preg_match('/\b(?:today|today\'s)\b/i', $text))                                                             return 'today';
        if (preg_match('/\byesterday\b/i', $text))                                                                      return 'yesterday';
        if (preg_match('/\bthis\s*week\b/i', $text))                                                                    return 'this_week';
        if (preg_match('/\b(?:this\s*month|current\s*month)\b/i', $text))                                                return 'this_month';
        if (preg_match('/\b(?:last\s*(?:1\s*)?month|previous\s*month)\b/i', $text))                                      return 'last_month';
        if (preg_match('/\bthis\s*year\b/i', $text))                                                                     return 'this_year';
        if (preg_match('/\blast\s*year\b/i', $text))                                                                     return 'last_year';

        return null;
    }

    /**
     * Detect all date ranges present in normalized text for multi-timeframe queries.
     */
    public static function detectDateRanges(string $text): array
    {
        $ranges = [];
        if (preg_match('/\b(?:today|today\'s)\b/i', $text)) {
            $ranges[] = 'today';
        }
        if (preg_match('/\byesterday\b/i', $text)) {
            $ranges[] = 'yesterday';
        }
        if (preg_match('/\bthis\s*week\b/i', $text)) {
            $ranges[] = 'this_week';
        }
        if (preg_match('/\b(?:this\s*month|current\s*month)\b/i', $text)) {
            $ranges[] = 'this_month';
        }
        if (preg_match('/\b(?:last\s*(?:1\s*)?month|previous\s*month)\b/i', $text)) {
            $ranges[] = 'last_month';
        }
        if (preg_match('/\b(?:last\s*30\s*days|30\s*days|past\s*month|last\s*1\s*month|past\s*1\s*month)\b/i', $text)) {
            $ranges[] = '30_days';
        }
        if (preg_match('/\b(?:last\s*7\s*days|7\s*days|past\s*7\s*days|last\s*week|past\s*week)\b/i', $text)) {
            $ranges[] = '7_days';
        }
        if (preg_match('/\bthis\s*year\b/i', $text)) {
            $ranges[] = 'this_year';
        }
        if (preg_match('/\blast\s*year\b/i', $text)) {
            $ranges[] = 'last_year';
        }
        if (preg_match('/\b(?:till\s*(?:today|now|date)|until\s*(?:today|now)|to\s*date|so\s*far|up\s*(?:to|till)\s*now|as\s*of\s*(?:today|now)|all[\s_]*time|overall|lifetime)\b/i', $text)) {
            $ranges[] = 'all_time';
        }
        return array_values(array_unique($ranges));
    }

    /**
     * Detect provider status from normalized text.
     */
    public static function detectProviderStatus(string $text): ?string
    {
        if (preg_match('/\b(?:non\s*verified|unverified|old\s*sp|old\s*non\s*verified)\b/i', $text)) return 'non_verified';
        if (preg_match('/\b(?:verified|fully\s*verified|active\s*verified)\b/i', $text))              return 'verified';
        if (preg_match('/\b(?:active|approved)\b/i', $text))                                          return 'active';
        if (preg_match('/\b(?:inactive|suspended)\b/i', $text))                                       return 'inactive';
        if (preg_match('/\b(?:pending\s*kyc|kyc\s*pending)\b/i', $text))                             return 'pending_kyc';
        return null;
    }

    /**
     * Detect booking status from normalized text.
     */
    public static function detectBookingStatus(string $text): ?string
    {
        if (preg_match('/\b(?:new|unassigned|pending|open)\b/i', $text))            return 'new';
        if (preg_match('/\b(?:on[-_\s]*hold|onhold|paused)\b/i', $text))                    return 'onhold';
        if (preg_match('/\b(?:halted|halt)\b/i', $text))                                    return 'halted';
        if (preg_match('/\b(?:deleted|removed|trash)\b/i', $text))                           return 'deleted';
        if (preg_match('/\b(?:completed|finished|delivered)\b/i', $text))                    return 'completed';
        if (preg_match('/\b(?:cancelled|canceled|cancellation|cancellations)\b/i', $text))   return 'cancelled';
        if (preg_match('/\b(?:active|ongoing|confirmed|accepted|in\s*progress)\b/i', $text)) return 'active';
        return null;
    }

    /**
     * Determine if the query is about providers, customers, or ambiguous.
     */
    public static function detectRoleContext(string $text): string
    {
        $hasProvider = (bool) preg_match('/\b(?:providers?|sps?|service\s*providers?|walkers?|groomers?|trainers?|sitters?|boarders?|partners?)\b/i', $text);
        $hasCustomer = (bool) preg_match('/\b(?:customers?|clients?|pet\s*parents?|pet\s*owners?|members?|users?)\b/i', $text);

        if ($hasProvider && !$hasCustomer) return 'provider';
        if ($hasCustomer && !$hasProvider) return 'customer';
        if ($hasProvider && $hasCustomer)  return 'both';
        return 'ambiguous';
    }

    /**
     * Extract a person's name from the message for individual lookups.
     * Removes all known entity tokens, filler words, and returns the remaining candidate.
     */
    private static function extractSearchName(string $original, string $normalized): ?string
    {
        // Work from original to preserve casing of names
        $cleaned = $original;

        // Remove conversational prefixes
        $cleaned = preg_replace('/\b(?:i\s*want\s*to\s*know(?:\s*about)?|can\s*you\s*(?:tell|show|give)\s*me(?:\s*about)?|please\s*(?:check|tell|show|give|find|get)(?:\s*about)?|let\s*me\s*know(?:\s*about)?|tell\s*me\s*about|show\s*me|what\s*is\s*the|details\s*(?:of|for|about)|profile\s*(?:of|for|about))\b/i', ' ', $cleaned);

        // Remove role/entity tokens
        $cleaned = preg_replace('/\b(?:service\s*providers?|providers?|sp|sps|partners?|clients?|customers?|pet\s*parents?|pet\s*owners?|users?|members?)\b/i', ' ', $cleaned);

        // Remove business domain terms
        $cleaned = preg_replace('/\b(?:reconcil\w*|recoinciliation|wallet|calculation|calculating|audit|ledger|platform|api|logic|system|petsfolio|earnings?|revenue|payout|payouts|withdraw\w*|commission|overview|summary|status|account|record|records|details|profile|info|information|cancellation|cancelled|canceled|halted|funnel|dropoff|dropoffs|dropoff_reasons|reasons?|tracking|untracked|verification|calls?|interview|interviews|telephonic|executive|notifications?|bidding|bids?|community|channels?|posts?|loyalty|points?|rewards?|bookings?|jobs?|requests?|approval|pending|queue|fees?|registration|events?|activity|stream|quality|flagged|sentiment|off_platform|contact\s*sharing|amount|collected|collections?|total|sum|average|avg|rate|rates|percentage|only|paid)\b/i', ' ', $cleaned);

        // Remove common stopwords
        $cleaned = preg_replace('/\b(?:he|she|they|is|are|was|were|a|an|the|there|in|at|on|to|of|for|about|or|not|and|but|so|if|check|find|search|know|want|tell|me|who|this|any|registered|active|inactive|how|much|many|wrong|check|clearly|said|has|have|had)\b/i', ' ', $cleaned);

        // Remove SQL-like terms
        $cleaned = preg_replace('/\b(?:select|from|where|like|order|by|asc|desc|table|database)\b/i', ' ', $cleaned);

        // Remove special chars (preserve @. for emails)
        $cleaned = preg_replace('/[^\w\s@.]/', ' ', $cleaned);

        // Clean up whitespace
        $candidate = trim(preg_replace('/\s+/', ' ', $cleaned));

        // Validate: must be at least 2 chars, not a stopword, not a number
        if (
            !empty($candidate)
            && strlen($candidate) >= 2
            && !is_numeric($candidate)
            && !BusinessDictionary::isStopwordCandidate($candidate)
        ) {
            return $candidate;
        }

        return null;
    }

    /**
     * Try to extract a provider/customer name from conversation history.
     * Used for follow-up queries where the subject was mentioned earlier.
     */
    private static function extractNameFromHistory(string $historyNorm): ?string
    {
        // Look for "reconciliation of <Name>" or "about <Name>" patterns
        if (preg_match('/\b(?:reconciliation\s*(?:of|for|summary:?)|about)\s+([A-Za-z]+(?:\s+[A-Za-z]+)?)\b/i', $historyNorm, $m)) {
            $cand = $m[1];
            $skipWords = ['summary', 'findings', 'recommendation', 'detected', 'wallet', 'provider', 'customer', 'client'];
            if (!in_array(strtolower($cand), $skipWords)) {
                return $cand;
            }
        }

        return null;
    }
}
