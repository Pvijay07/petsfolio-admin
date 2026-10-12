<?php

namespace App\Libraries\AIAnalyst;

/**
 * SemanticResolver
 * 
 * Implements Step 2 & 3 of the BI Accuracy Layer:
 * User Question -> Intent/Entity Detection -> Business Definition Resolver -> Validated Metric/Parameter Mapping.
 * 
 * Prevents LLM hallucinations, rejects SQL guessing, and enforces exact business definitions.
 */
class SemanticResolver
{
    /**
     * Resolves user question into deterministic metric executions.
     * Returns an array of planned metric function calls with canonical arguments.
     */
    public static function resolve(string $userMessage, ?string $historyText = null): ?array
    {
        $msgLower = strtolower(trim($userMessage));

        // 1. Phone Number Detection (Top Priority Dual Verification)
        if (preg_match('/\b[6-9]\d{9}\b/', $userMessage, $phoneMatches)) {
            $phone = $phoneMatches[0];
            return [
                'type'    => 'phone_lookup',
                'metrics' => [
                    ['name' => 'provider_stats', 'args' => ['provider_search' => $phone]],
                    ['name' => 'client_profile_lookup', 'args' => ['client_search' => $phone]]
                ]
            ];
        }

        // 2. Client / Customer Aggregate Headcount (e.g. "how many clients are there in petsfolio", "total registered clients", "client count")
        $isClientHeadcount = (bool) preg_match('/\b(how\s*many\s*(?:registered\s*|active\s*)?(?:clients?|customers?|users?|members?)|(?:total|count|number\s*of)\s*(?:registered\s*|active\s*)?(?:clients?|customers?|users?|members?)|client\s*count|customer\s*count)\b/i', $msgLower)
            && !preg_match('/\b(service\s*providers?|providers?|sp\s*count|sps|walkers?|groomers?|trainers?|sitters?|boarders?|posts?|channels?|loyalty|points?)\b/i', $msgLower);

        if ($isClientHeadcount) {
            $dr = (str_contains($msgLower, 'today') || str_contains($msgLower, 'this month') || str_contains($msgLower, '30 days')) ? '30_days' : 'all_time';
            return [
                'type'    => 'client_aggregate',
                'metrics' => [
                    ['name' => 'active_customers', 'args' => ['date_range' => $dr, 'sort_by' => 'bookings']]
                ]
            ];
        }

        // 2b. Repeat / Returning Clients & Customers (e.g. "any repeated clients are there", "repeat clients", "repeat customers", "retention")
        $isRepeatClients = (bool) preg_match('/\b(repeat(?:ed)?\s*(?:clients?|customers?|users?|members?)|returning\s*(?:clients?|customers?|users?)|frequent\s*(?:clients?|customers?)|customer\s*retention|client\s*retention)\b/i', $msgLower);
        if ($isRepeatClients) {
            $dr = (str_contains($msgLower, 'today') || str_contains($msgLower, 'this month') || str_contains($msgLower, '30 days')) ? '30_days' : 'all_time';
            return [
                'type'    => 'repeat_customers',
                'metrics' => [
                    ['name' => 'repeat_customers', 'args' => ['date_range' => $dr, 'sort_by' => 'bookings']]
                ]
            ];
        }

        // 2c. Client Profile / Customer Lookup (Individual)
        $isGeneralCustomer = (bool) preg_match('/\b(repeat(?:ed)?\s*(?:clients?|customers?|users?|members?)|churned|customer\s*retention|customer\s*growth|client\s*wallets?|client\s*collections?|client\s*agreements?|amount\s*received\s*from\s*clients?|client\s*payments?)\b/i', $msgLower);
        $isClientInquiry = !$isGeneralCustomer && (
            (
                preg_match('/\b(client|customer|pet\s*parent)\b/i', $msgLower) &&
                !preg_match('/\b(service\s*provider|providers?|sp\s*count|sps)\b/i', $msgLower)
            ) ||
            preg_match('/\b(is\s+[a-zA-Z0-9_]+\s+(?:active|inactive|there|registered)|check\s+client|about\s+[a-zA-Z0-9_]+\s+client)\b/i', $msgLower)
        );

        if ($isClientInquiry) {
            $cleaned = preg_replace('/\b(i\s*want\s*to\s*know(?:\s*about)?|can\s*you\s*(?:tell|show|give)\s*me(?:\s*about)?|please\s*(?:check|tell|show|give|find|get)(?:\s*about)?|let\s*me\s*know(?:\s*about)?|tell\s*me\s*about|show\s*me|what\s*is\s*the|details\s*(?:of|for|about)|profile\s*(?:of|for|about))\b/i', ' ', $userMessage);
            $cleaned = preg_replace('/\b(client|customer|pet\s*parent|user|details|profile|info|information|about|is|there|in|active|inactive|or|not|please|check|find|search|status|account|know|want|tell|me|to|select|from|where|like|order|by|asc|desc|table|users)\b/i', ' ', $cleaned);
            $cleaned = preg_replace('/[^\w\s@.]/u', ' ', $cleaned);
            $clientSearch = trim(preg_replace('/\s+/', ' ', $cleaned));

            if (!empty($clientSearch) && strlen($clientSearch) >= 2 && !BusinessDictionary::isStopwordCandidate($clientSearch)) {
                return [
                    'type'    => 'client_lookup',
                    'metrics' => [
                        ['name' => 'client_profile_lookup', 'args' => ['client_search' => $clientSearch]]
                    ]
                ];
            }
        }

        // 2d. Elliptic Conversational Follow-ups (e.g. "how many are there currently?", "how many currently?", "how many total?")
        $isEllipticCount = (bool) preg_match('/^(?:so\s+)?how\s*many\s*(?:are\s*there\s*)?(?:currently|today|total|in\s*total|now|active|new)?\??$/i', trim($msgLower))
            || (bool) preg_match('/\b(how\s*many\s*are\s*there\s*(?:currently|now|today|total)?|how\s*many\s*(?:currently|total))\b/i', $msgLower);

        if ($isEllipticCount) {
            $hasExplicitEntity = (bool) preg_match('/\b(walkers?|trainers?|groomers?|sitters?|boarders?|providers?|sps|clients?|customers?|users?|members?|jobs?|bookings?|requests?)\b/i', $msgLower);
            if (!$hasExplicitEntity && !empty($historyText)) {
                $histLower = strtolower($historyText);
                if (preg_match('/\b(jobs?|service\s*requests?|bookings?|orders?)\b/i', $histLower)) {
                    return [
                        'type'    => 'jobs_followup',
                        'metrics' => [
                            ['name' => 'active_jobs', 'args' => []],
                            ['name' => 'bookings_summary', 'args' => ['date_range' => 'all_time']]
                        ]
                    ];
                } elseif (preg_match('/\b(clients?|customers?|pet\s*parents?)\b/i', $histLower)) {
                    return [
                        'type'    => 'client_followup',
                        'metrics' => [
                            ['name' => 'active_customers', 'args' => ['date_range' => 'all_time', 'sort_by' => 'bookings']]
                        ]
                    ];
                }
            }
        }

        // 3. Operational Queues (Manager Approval, Fee Pending, Waiting for Call)
        if (preg_match('/\b(manager\s*approval|approval\s*(?:queue|pending)|pending\s*(?:manager\s*)?approval|awaiting\s*(?:manager\s*)?approval)\b/i', $msgLower)) {
            $svc = self::detectService($msgLower);
            return [
                'type'    => 'operational_queue',
                'metrics' => [
                    ['name' => 'provider_stats', 'args' => ['status' => 'approval', 'service_type' => $svc, 'date_range' => 'all_time']]
                ]
            ];
        }

        if (preg_match('/\b(fee\s*pending|registration\s*fee\s*pending|pending\s*registration|unpaid\s*registration|pending\s*fee|unpaid\s*fee)\b/i', $msgLower)) {
            $svc = self::detectService($msgLower);
            return [
                'type'    => 'operational_queue',
                'metrics' => [
                    ['name' => 'provider_stats', 'args' => ['status' => 'pending_registration', 'service_type' => $svc, 'date_range' => 'all_time']]
                ]
            ];
        }

        // 3a-1. Client Booking Payment Transactions Intent (Mirroring Admin Panel -> Finance -> Payments)
        $isBookingPaymentsQuery = (bool) preg_match('/\b(client\s*payments?|booking\s*payments?|payments?\s*done\s*for\s*bookings?|payments?\s*transactions?|how\s*many\s*(?:are\s*)?success(?:ful)?[\s,]+fail(?:ed)?|what\s*is\s*the\s*fail\s*reason|failure\s*reasons?|why\s*(?:did\s*)?payments?\s*fail|failed\s*payments?|admin\.petsfolio\.in\/payments)\b/i', $msgLower)
            && !preg_match('/\b(payout|withdrawal|cashfree\s*balance|executive\s*payout)\b/i', $msgLower);

        if ($isBookingPaymentsQuery) {
            $statusFilter = null;
            if (preg_match('/\b(success(?:ful)?)\b/i', $msgLower)) {
                $statusFilter = 'success';
            } elseif (preg_match('/\b(fail(?:ed)?|failure)\b/i', $msgLower)) {
                $statusFilter = 'failed';
            } elseif (preg_match('/\b(pending)\b/i', $msgLower)) {
                $statusFilter = 'pending';
            }
            $dr = (str_contains($msgLower, 'today')) ? 'today' : (str_contains($msgLower, '30 days') ? '30_days' : 'all_time');
            return [
                'type'    => 'booking_payments_overview',
                'metrics' => [
                    ['name' => 'booking_payments_overview', 'args' => ['date_range' => $dr, 'status_filter' => $statusFilter ?? 'all']]
                ]
            ];
        }

        // 3a-1b. Executive Payouts, Wallets & Withdrawals Intent
        $isExecutivePayoutQuery = (bool) preg_match('/\b(executive\s*(?:payouts?|withdrawals?|wallets?|earnings?)|verification\s*executive\s*(?:payouts?|withdrawals?|wallets?|earnings?)|executive\s*payouts?\s*also\s*there)\b/i', $msgLower)
            || (bool) preg_match('/\b(how\s*much\s+(?:did\s+)?[a-zA-Z]+\s+(?:withdraw|withdrawled|withdrawn|earn|earned))\b/i', $msgLower)
            || ((bool) preg_match('/\b(abhinaya|varalaxmi|tara\s*devi|divya\s*executive)\b/i', $msgLower) && (bool) preg_match('/\b(withdraw|withdrawled|withdrawn|payout|payouts|wallet|earnings?|earned)\b/i', $msgLower));

        if ($isExecutivePayoutQuery) {
            $search = null;
            if (preg_match('/\b(how\s*much\s+(?:did\s+)?([a-zA-Z]+)\s+(?:withdraw|withdrawled|withdrawn|earn|earned))\b/i', $msgLower, $wMatches)) {
                if (!empty($wMatches[2]) && !in_array(strtolower($wMatches[2]), ['he', 'she', 'they', 'the', 'a', 'sp', 'provider'])) {
                    $search = $wMatches[2];
                }
            } elseif (preg_match('/\b(abhinaya|varalaxmi|tara\s*devi|divya\s*executive|divya|tara)\b/i', $msgLower, $nm)) {
                $search = $nm[1];
            }

            return [
                'type'    => 'executive_payouts_overview',
                'metrics' => [
                    ['name' => 'executive_payouts_overview', 'args' => ['executive_search' => $search, 'date_range' => 'all_time']]
                ]
            ];
        }

        // 3a-2. SP Verification Calls / Telephonic Interviews / Executive Verification Calls Intent
        $isVerificationCallQuery = (bool) preg_match('/\b(verifications?\s*calls?|verification\s*interviews?|verifier\s*calls?|telephonic\s*interviews?|executive\s*calls?|executive\s*call\s*records?|sp\s*verifications?\s*calls?)\b/i', $msgLower)
            || (preg_match('/\b(verifications?|verifier)\b/i', $msgLower) && preg_match('/\b(calls?|calling|happened|happen|done|conducted|interview|interviews)\b/i', $msgLower));

        if ($isVerificationCallQuery) {
            $isToday = (bool) preg_match('/\b(today|today\'s)\b/i', $msgLower);
            $isYesterday = (bool) preg_match('/\b(yesterday)\b/i', $msgLower);
            $isAllTime = (bool) preg_match('/\b(till\s*(?:today|now|date)|all\s*time|overall|total|lifetime)\b/i', $msgLower);
            $dr = $isToday ? 'today' : ($isYesterday ? 'yesterday' : ($isAllTime ? 'all_time' : (str_contains($msgLower, 'month') ? 'this_month' : 'today')));

            return [
                'type'    => 'call_verification_overview',
                'metrics' => [
                    ['name' => 'call_verification_overview', 'args' => ['date_range' => $dr]]
                ]
            ];
        }

        // 3a-3. Off-Platform Discussions / Contact Sharing / Call Quality & Flagged Calls Intent
        if (preg_match('/\b(off[-_\s]*platform|contact\s*sharing|outside\s*(?:the\s*)?platform|bypass\s*(?:the\s*)?platform|call\s*quality|call\s*qa|qa\s*score|flagged\s*calls?|abusive\s*calls?|call\s*sentiment)\b/i', $msgLower)) {
            $isToday = (bool) preg_match('/\b(today|today\'s)\b/i', $msgLower);
            $dr = $isToday ? 'today' : 'all_time';
            return [
                'type'    => 'call_ai_quality_analytics',
                'metrics' => [
                    ['name' => 'call_ai_quality_analytics', 'args' => ['date_range' => $dr]]
                ]
            ];
        }

        if (preg_match('/\b(waiting\s*(?:for)?\s*call|call\s*verification\s*queue)\b/i', $msgLower)
            || (preg_match('/\b(pending\s*verification)\b/i', $msgLower) && !preg_match('/\b(calls?|happened|happen|done|interview)\b/i', $msgLower))) {
            $svc = self::detectService($msgLower);
            return [
                'type'    => 'operational_queue',
                'metrics' => [
                    ['name' => 'provider_stats', 'args' => ['status' => 'waiting for call', 'service_type' => $svc, 'date_range' => 'all_time']]
                ]
            ];
        }

        // 3a-4. Conversion Funnel & Drop-off Analysis Intent
        $isFunnelQuery = (bool) preg_match('/\b(funnel|conversion\s*funnel|drop[-_\s]*offs?|why\s*(?:are\s*)?clients?\s*(?:not\s*)?(?:completing|booking|paying)|why\s*bookings?\s*(?:halt|fail|cancel)|bottleneck|completion\s*funnel|largest\s*drop[-_\s]*off|cancellation\s*reasons?|halt\s*reasons?|why\s*(?:do|are)\s*bookings\s*cancelled)\b/i', $msgLower);
        if ($isFunnelQuery) {
            $svc = self::detectService($msgLower) ?: null;
            $dr = (str_contains($msgLower, 'today') || str_contains($msgLower, "today's")) ? 'today' : (str_contains($msgLower, 'month') ? 'this_month' : (str_contains($msgLower, '30 days') ? '30_days' : 'all_time'));

            if (preg_match('/\b(why|reasons?|causes?|halt\s*reasons?|cancellation\s*reasons?)\b/i', $msgLower) && !preg_match('/\b(funnel|stages?)\b/i', $msgLower)) {
                return [
                    'type'    => 'dropoff_reasons',
                    'metrics' => [
                        ['name' => 'dropoff_reasons_analysis', 'args' => ['service_type' => $svc, 'date_range' => $dr]]
                    ]
                ];
            }

            return [
                'type'    => 'funnel_analysis',
                'metrics' => [
                    ['name' => 'funnel_analysis', 'args' => ['funnel_type' => $svc, 'date_range' => $dr]],
                    ['name' => 'dropoff_reasons_analysis', 'args' => ['service_type' => $svc, 'date_range' => $dr]]
                ]
            ];
        }

        // 3a-5. Business Events & Platform Activity Stream Intent
        if (preg_match('/\b(business\s*events?|platform\s*events?|events?\s*overview|event\s*stream|activity\s*stream|event\s*log)\b/i', $msgLower)) {
            $dr = (str_contains($msgLower, 'today')) ? 'today' : (str_contains($msgLower, '30 days') ? '30_days' : 'all_time');
            return [
                'type'    => 'business_events',
                'metrics' => [
                    ['name' => 'business_events_overview', 'args' => ['date_range' => $dr]]
                ]
            ];
        }

        // 3b-0. Provider Revenue & Earnings Overview (e.g. "how much providers earnings average and who earned maximum,minimum?", "top earning providers", "provider earnings", "average provider earnings")
        $isProviderEarningsQuery = (bool) preg_match('/\b(provider(?:s|\'s)?\s*earnings?|sp\s*earnings?|providers?\s*revenue|earnings?\s*of\s*providers?|how\s*much\s*providers?\s*(?:earn|earned|earnings?)|earned\s*(?:maximum|minimum|max|min|highest|lowest)|(?:maximum|minimum|max|min|highest|lowest|average|avg)\s*(?:provider\s*)?earnings?|top\s*(?:earning|earners)\s*(?:providers?|walkers?|groomers?|trainers?|sps?)?)\b/i', $msgLower);

        if ($isProviderEarningsQuery) {
            $isTillTodayOrAllTime = (bool) preg_match('/\b(till\s*(?:today|now|date)|until\s*(?:today|now)|to\s*date|so\s*far|up\s*(?:to|till)\s*now|as\s*of\s*(?:today|now)|all\s*time|overall|lifetime|total|ever)\b/i', $msgLower);
            $dr = $isTillTodayOrAllTime ? 'all_time' : (str_contains($msgLower, 'today') ? 'today' : (str_contains($msgLower, 'month') ? 'this_month' : 'all_time'));
            $svc = self::detectService($msgLower);

            return [
                'type'    => 'provider_revenue_overview',
                'metrics' => [
                    [
                        'name' => 'provider_revenue_overview',
                        'args' => [
                            'date_range'   => $dr,
                            'service_type' => $svc
                        ]
                    ]
                ]
            ];
        }

        // 3a-6. Provider & Client Financial Reconciliation & Wallet Audit Intent
        // (e.g. "check reconciliation of Sunny kore", "wrong check how wallet calculation is happening in api logic", "reconcile Sunny kore")
        $isReconQuery = (bool) preg_match('/\b(?:reconcil\w*|wallet\s*(?:calculation|audit|logic|mismatch|check|discrepancy)|api\s*logic)\b/i', $msgLower);
        if ($isReconQuery) {
            $cleaned = preg_replace('/\b(check\s*how|how|check|show|tell|view|audit|reconcil(?:e|iation|ing)?|recoinciliation|wallet|calculation|calculating|is|happening|in|api|logic|platform|ledger|system|of|for|about|provider|sp|service\s*provider|account|details|status|please|wrong|overview|summary)\b/i', ' ', $userMessage);
            $cleaned = preg_replace('/[^\w\s]/u', ' ', $cleaned);
            $cand = trim(preg_replace('/\s+/', ' ', $cleaned));

            if ((empty($cand) || strlen($cand) < 2 || BusinessDictionary::isStopwordCandidate($cand)) && !empty($historyText)) {
                if (preg_match('/\b(?:reconciliation\s*(?:of|for|summary:?)|sunny\s*kore)\s*([A-Za-z]+(?:\s+[A-Za-z]+)?)\b/i', $historyText, $hm)) {
                    if (!in_array(strtolower($hm[1]), ['summary', 'findings', 'recommendation', 'detected', 'wallet'])) {
                        $cand = $hm[1];
                    }
                }
                if (empty($cand) && preg_match('/\b(Sunny\s*kore)\b/i', $historyText, $hm)) {
                    $cand = $hm[1];
                }
            }

            $isClientQuery = (str_contains($msgLower, 'client') || str_contains($msgLower, 'customer') || str_contains($msgLower, 'user'))
                && !str_contains($msgLower, 'provider') && !str_contains($msgLower, 'sp ');

            if ($isClientQuery) {
                return [
                    'type'    => 'client_wallet_reconciliation',
                    'metrics' => [
                        ['name' => 'client_wallet_reconciliation', 'args' => ['client_search' => $cand ?: null]]
                    ]
                ];
            } else {
                return [
                    'type'    => 'provider_reconciliation_overview',
                    'metrics' => [
                        ['name' => 'provider_reconciliation_overview', 'args' => ['provider_search' => $cand ?: null]]
                    ]
                ];
            }
        }

        // 3b. Provider Payout Requests / Cashfree Payout Balance / Withdrawal Requests
        if (preg_match('/\b(payout\s*requests?|payout\s*balance|pending\s*payouts?|withdrawal\s*requests?|provider\s*payouts?|payouts?|withdrawals?|cashfree\s*(?:payout\s*)?balance)\b/i', $msgLower)) {
            return [
                'type'    => 'payout_requests',
                'metrics' => [
                    ['name' => 'payout_balance_overview', 'args' => ['date_range' => 'all_time']]
                ]
            ];
        }

        // 3b-2. Walk & Session Tracking / Untracked Walks / Untracked Sessions Intent
        $isUntrackedQuery = (bool) preg_match('/\b(untracked|not\s*tracked|without\s*tracking|missed\s*tracking)\b/i', $msgLower)
            || (preg_match('/\b(walks?|sessions?)\b/i', $msgLower) && preg_match('/\b(untracked|missed|tracking|short\s*walk|suspicious\s*walk)\b/i', $msgLower));

        if ($isUntrackedQuery) {
            $isTillTodayOrAllTime = (bool) preg_match('/\b(till\s*(?:today|now|date)|until\s*(?:today|now)|to\s*date|so\s*far|up\s*(?:to|till)\s*now|as\s*of\s*(?:today|now)|all\s*time|overall|lifetime|total|ever)\b/i', $msgLower);
            $isActive = (bool) preg_match('/\b(active|ongoing|confirmed|accepted|current|in\s*progress)\b/i', $msgLower);

            $dr = $isTillTodayOrAllTime ? 'all_time' : (str_contains($msgLower, 'today') ? 'today' : (str_contains($msgLower, 'month') ? 'this_month' : 'all_time'));

            return [
                'type'    => 'walking_tracking_analytics',
                'metrics' => [
                    [
                        'name' => 'walking_tracking_analytics',
                        'args' => [
                            'date_range'     => $dr,
                            'status'         => 'untracked',
                            'booking_status' => $isActive ? 'active' : 'all'
                        ]
                    ]
                ]
            ];
        }

        // 3b-3. Community Channels, Posts & Feed Overview Intent
        $isCommunityQuery = (bool) preg_match('/\b(community\s*channels?|community\s*posts?|community\s*feeds?|community\s*groups?|channels?\s*list|how\s*many\s*(?:community\s*)?channels?|community\s*overview|community\s*discussion|pet\s*community|how\s*many\s*posts?|total\s*posts?|admin\s*posts?|client\s*posts?|provider\s*posts?|sp\s*posts?)\b/i', $msgLower)
            || (preg_match('/\b(posts?|channels?)\b/i', $msgLower) && preg_match('/\b(community|admin|client|provider|sp|feed|discussions?|overview|channel|channels|how\s*many|total|breakdown)\b/i', $msgLower));

        if ($isCommunityQuery) {
            $isTillTodayOrAllTime = (bool) preg_match('/\b(till\s*(?:today|now|date)|until\s*(?:today|now)|to\s*date|so\s*far|up\s*(?:to|till)\s*now|as\s*of\s*(?:today|now)|all\s*time|overall|lifetime|total|ever)\b/i', $msgLower);
            $dr = $isTillTodayOrAllTime ? 'all_time' : (str_contains($msgLower, 'today') ? 'today' : (str_contains($msgLower, 'month') ? 'this_month' : 'all_time'));

            return [
                'type'    => 'community_overview',
                'metrics' => [
                    [
                        'name' => 'community_overview',
                        'args' => [
                            'date_range' => $dr
                        ]
                    ]
                ]
            ];
        }

        // 3b2. Loyalty & Rewards Intent (e.g. "how many total loyalty points there", "who has maximum,minimum points", "loyalty program", "reward points", "loyalty wallet")
        $isLoyaltyQuery = (bool) preg_match('/\b(loyalty|loyalty\s*points?|rewards?|reward\s*points?|spin\s*wheel|loyalty\s*wallet)\b/i', $msgLower)
            || ((bool) preg_match('/\b(maximum|minimum|max|min|highest|lowest)\s*(?:loyalty\s*)?points?\b/i', $msgLower));

        if ($isLoyaltyQuery) {
            return [
                'type'    => 'loyalty_overview',
                'metrics' => [
                    [
                        'name' => 'loyalty_overview',
                        'args' => [
                            'date_range' => 'all_time'
                        ]
                    ]
                ]
            ];
        }

        // 3c. Bookings / Jobs / Service Requests Intent (e.g. "how many new jobs there till today?", "how many new jobs there?", "halted bookings", "deleted bookings", "completed bookings", "cancelled bookings")
        $isJobOrBookingQuery = (bool) preg_match('/\b(jobs?|bookings?|service\s*requests?|orders?|cancellations?|cancelled|canceled|halted)\b/i', $msgLower)
            && !preg_match('/\b(calls?|community|tickets?|sms|campaigns?|payouts?|withdrawals?|posts?|channels?|loyalty|points?)\b/i', $msgLower);

        if ($isJobOrBookingQuery) {
            $isTillTodayOrAllTime = (bool) preg_match('/\b(till\s*(?:today|now|date)|until\s*(?:today|now)|to\s*date|so\s*far|up\s*(?:to|till)\s*now|as\s*of\s*(?:today|now)|all\s*time|overall|lifetime|total|ever)\b/i', $msgLower);
            $isStrictToday = !$isTillTodayOrAllTime && (bool) preg_match('/\b(today|today\'s)\b/i', $msgLower);

            $svc  = self::detectService($msgLower);
            $city = self::detectCity($msgLower);

            // Determine status
            $status = null;
            if (preg_match('/\b(new|unassigned|pending|created|open)\b/i', $msgLower)) {
                $status = 'new';
            } elseif (preg_match('/\b(on[-_\s]*hold|onhold|paused)\b/i', $msgLower)) {
                $status = 'onhold';
            } elseif (preg_match('/\b(halted|halt)\b/i', $msgLower)) {
                $status = 'halted';
            } elseif (preg_match('/\b(deleted|removed|trash)\b/i', $msgLower)) {
                $status = 'deleted';
            } elseif (preg_match('/\b(completed|finished|delivered)\b/i', $msgLower)) {
                $status = 'completed';
            } elseif (preg_match('/\b(cancelled|canceled|cancellation|cancellations)\b/i', $msgLower)) {
                $status = 'cancelled';
            } elseif (preg_match('/\b(active|ongoing|confirmed|accepted)\b/i', $msgLower)) {
                return [
                    'type'    => 'active_jobs',
                    'metrics' => [
                        ['name' => 'active_jobs', 'args' => ['service_type' => $svc, 'city' => $city]]
                    ]
                ];
            }

            // Default to all_time when no explicit date phrase is given (user expects totals, not last 30 days)
            $dr = $isTillTodayOrAllTime ? 'all_time' : ($isStrictToday ? 'today' : (str_contains($msgLower, 'month') ? 'this_month' : 'all_time'));

            $bArgs = ['date_range' => $dr];
            if ($status !== null) {
                $bArgs['status'] = $status;
            }
            if ($svc !== null) {
                $bArgs['service_type'] = $svc;
            }
            if ($city !== null) {
                $bArgs['city'] = $city;
            }

            return [
                'type'    => 'bookings_summary',
                'metrics' => [
                    ['name' => 'bookings_summary', 'args' => $bArgs]
                ]
            ];
        }

        // 4. Headcount / Aggregate Provider Stats (Strictly Protected from Name Extraction)
        $isAggregateHeadcount = (bool) preg_match('/\b(how\s*many|count|number\s*(?:of)?|total\s*(?:number\s*of)?|breakdown|density|growth|trend|summary|pipeline)\b/i', $msgLower)
            && (bool) preg_match('/\b(walkers?|trainers?|groomers?|sitters?|boarders?|providers?|sps|service\s*providers?)\b/i', $msgLower)
            && !preg_match('/\b(posts?|comments?|channels?|notifications?|bids?|bidding|earnings?|revenue|payouts?|withdrawals?)\b/i', $msgLower);

        if ($isAggregateHeadcount) {
            $svc    = self::detectService($msgLower);
            $city   = self::detectCity($msgLower);
            $status = self::detectStatus($msgLower);
            return [
                'type'    => 'provider_aggregate',
                'metrics' => [
                    [
                        'name' => 'provider_stats',
                        'args' => [
                            'service_type' => $svc,
                            'city'         => $city,
                            'status'       => $status,
                            'date_range'   => 'all_time'
                        ]
                    ]
                ]
            ];
        }

        // 4b. Provider Notification & Bidding Activity Intent
        // Matches: "which provider not receiving notifications", "who receiving but not active", "active but submitting bids", "analyze notifications logs"
        $isNotifBiddingQuery = (bool) preg_match('/\b(notifications?|push\s*notifications?|fcm|tokens?)\b/i', $msgLower)
            && (bool) preg_match('/\b(providers?|sps?|bids?|bidding|quotations?|receiving|not\s*receiving|active|inactive|logs?|who|which)\b/i', $msgLower);

        if ($isNotifBiddingQuery) {
            return [
                'type'    => 'provider_notification_bidding_analysis',
                'metrics' => [
                    [
                        'name' => 'provider_notification_bidding_analysis',
                        'args' => [
                            'date_range' => 'all_time'
                        ]
                    ]
                ]
            ];
        }

        // 5. Specific Individual Provider Search (e.g. "Who is Vishnu Vardhan", "About Vishnu Vardhan", "vishnu vardhan he is service provider?")
        $isDomainMetricInquiry = (bool) preg_match('/\b(payout|withdrawal|wallet|cashfree|ledger|commission|revenue|sales|earnings|refund|tax|gst)\b/i', $msgLower);
        $isProviderInquiry = (
            preg_match('/\b(service\s*providers?|providers?|sp|sps|partner|partners)\b/i', $msgLower) ||
            preg_match('/\b(about|who\s*is|profile\s*(?:of|for)?|details\s*(?:of|for)?|clearly\s*said)\b/i', $msgLower)
        ) && !$isAggregateHeadcount && !$isClientInquiry && !$isDomainMetricInquiry && !$isNotifBiddingQuery;

        if ($isProviderInquiry) {
            // Remove conversational prefixes
            $cleaned = preg_replace('/\b(i\s*want\s*to\s*know(?:\s*about)?|can\s*you\s*(?:tell|show|give)\s*me(?:\s*about)?|please\s*(?:check|tell|show|give|find|get)(?:\s*about)?|let\s*me\s*know(?:\s*about)?|tell\s*me\s*about|show\s*me|what\s*is\s*the|details\s*(?:of|for|about)?|profile\s*(?:of|for|about)?|who\s*is|about|clearly\s*said)\b/i', ' ', $userMessage);
            // Remove provider entity tokens and common conversational filler words
            $cleaned = preg_replace('/\b(service\s*providers?|providers?|sp|sps|partner|partners|he|she|they|is|are|a|an|the|there|in|at|active|inactive|or|not|please|check|find|search|status|account|know|want|tell|me|to|who|this|any|registered|on|platform|database|record|records)\b/i', ' ', $cleaned);
            $cleaned = preg_replace('/[^\w\s]/u', ' ', $cleaned);
            $cand = trim(preg_replace('/\s+/', ' ', $cleaned));

            if (!empty($cand) && strlen($cand) >= 2 && !BusinessDictionary::isStopwordCandidate($cand)) {
                return [
                    'type'    => 'provider_lookup',
                    'metrics' => [
                        ['name' => 'provider_stats', 'args' => ['provider_search' => $cand, 'date_range' => 'all_time']]
                    ]
                ];
            }
        }

        return null;
    }

    /**
     * Canonical service detection.
     */
    public static function detectService(string $msgLower): ?string
    {
        if (str_contains($msgLower, 'walk')) return 'walking';
        if (str_contains($msgLower, 'groom') || str_contains($msgLower, 'bath')) return 'grooming';
        if (str_contains($msgLower, 'train')) return 'training';
        if (str_contains($msgLower, 'sit')) return 'sitting';
        if (str_contains($msgLower, 'board')) return 'boarding';
        return null;
    }

    /**
     * Canonical city detection.
     */
    public static function detectCity(string $msgLower): ?string
    {
        if (str_contains($msgLower, 'hyderabad') || str_contains($msgLower, 'hyd') || str_contains($msgLower, 'secunderabad')) return 'Hyderabad';
        if (str_contains($msgLower, 'bangalore') || str_contains($msgLower, 'bengaluru') || str_contains($msgLower, 'blr')) return 'Bengaluru';
        if (str_contains($msgLower, 'mumbai') || str_contains($msgLower, 'bombay')) return 'Mumbai';
        if (str_contains($msgLower, 'delhi') || str_contains($msgLower, 'noida') || str_contains($msgLower, 'gurgaon')) return 'New Delhi';
        if (str_contains($msgLower, 'chennai') || str_contains($msgLower, 'madras')) return 'Chennai';
        if (str_contains($msgLower, 'pune')) return 'Pune';
        if (str_contains($msgLower, 'kolkata')) return 'Kolkata';
        return null;
    }

    /**
     * Canonical status detection.
     */
    public static function detectStatus(string $msgLower): ?string
    {
        if (preg_match('/\b(non\s*verified|unverified|old\s*sp|old\s*non\s*verified)\b/i', $msgLower)) return 'non_verified';
        if (preg_match('/\b(verified|fully\s*verified|active\s*verified)\b/i', $msgLower)) return 'verified';
        if (preg_match('/\b(active|approved)\b/i', $msgLower)) return 'active';
        if (preg_match('/\b(inactive|suspended)\b/i', $msgLower)) return 'inactive';
        if (preg_match('/\b(pending\s*kyc|kyc\s*pending)\b/i', $msgLower)) return 'pending_kyc';
        return null;
    }
}
