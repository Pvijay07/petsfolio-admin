<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * IntentRegistry
 * 
 * Single Source of Truth for all BI intents.
 * Each intent defines:
 *   - domain: Business domain (providers, customers, bookings, operations, analytics, identity)
 *   - patterns: Weighted regex patterns for detection (higher weight = stronger signal)
 *   - exclusions: Patterns that DISQUALIFY this intent (prevents false positives)
 *   - required_entities: Entity types that MUST be present
 *   - optional_entities: Entity types that CAN refine the query
 *   - metrics: Target metric function(s) to execute
 *   - priority: Tie-breaking priority (lower = higher priority)
 */
class IntentRegistry
{
    /**
     * Complete intent definitions.
     * Key = intent ID (e.g. "provider.lookup")
     */
    public static function getIntents(): array
    {
        return [
            // ─────────────────────────────────────────────
            // IDENTITY DOMAIN (highest priority — phone numbers)
            // ─────────────────────────────────────────────
            'identity.phone_lookup' => [
                'domain'   => 'identity',
                'priority' => 1,
                'patterns' => [
                    ['regex' => '/\b[6-9]\d{9}\b/', 'weight' => 100],
                ],
                'exclusions' => [],
                'required_entities' => ['phone'],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => ['provider_search' => '{phone}']],
                    ['name' => 'client_profile_lookup', 'arg_map' => ['client_search' => '{phone}']],
                ],
            ],

            'booking.quotation_lookup' => [
                'domain'   => 'bookings',
                'priority' => 2,
                'patterns' => [
                    ['regex' => '/\b(?:P?SF[a-zA-Z0-9]+)\b/i', 'weight' => 100],
                    ['regex' => '/\b(?:quotations?|quotes?|bids?|offers?)\b/i', 'weight' => 40],
                    ['regex' => '/\b(?:booking\s*(?:id|details?))\b/i', 'weight' => 30],
                ],
                'exclusions' => [],
                'required_entities' => ['booking_id'],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'quotation_details_lookup', 'arg_map' => ['booking_id' => '{booking_id}']],
                ],
            ],

            // ─────────────────────────────────────────────
            // CUSTOMER DOMAIN
            // ─────────────────────────────────────────────
            'customer.headcount' => [
                'domain'   => 'customers',
                'priority' => 10,
                'patterns' => [
                    ['regex' => '/\b(?:how\s*many|count|number\s*of|total)\s*(?:new\s*|registered\s*|active\s*)?(?:customers?|clients?|users?|members?)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:customers?|clients?|users?)\s*(?:have\s*)?(?:never|0|zero|not)\s*(?:made|placed|booked|have)\s*(?:a\s*)?(?:booking|bookings?|request|requests?)\b/i', 'weight' => 45],
                    ['regex' => '/\bnever\s*(?:made|placed|booked|done)\s*(?:a\s*)?(?:booking|bookings?|service\s*request)\b/i', 'weight' => 40],
                    ['regex' => '/\b(?:customers?|clients?)\s*with\s*(?:0|zero|no)\s*bookings?\b/i', 'weight' => 40],
                    ['regex' => '/\bdownloaded\s*(?:app\s*)?only\b/i', 'weight' => 35],
                    ['regex' => '/\b(?:new\s*)?(?:customers?|clients?|users?)\s*registered\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:customer|client)\s*count\b/i', 'weight' => 25],
                    ['regex' => '/\btotal\s*(?:registered\s*)?(?:customers?|clients?)\b/i', 'weight' => 25],
                ],
                'exclusions' => [
                    '/\b(?:providers?|sps?|walkers?|groomers?|trainers?|sitters?|boarders?|posts?|channels?|loyalty|points?|wallets?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'active_customers', 'arg_map' => ['date_range' => '{date_range|all_time}', 'sort_by' => 'bookings']],
                ],
            ],

            'customer.repeat' => [
                'domain'   => 'customers',
                'priority' => 11,
                'patterns' => [
                    ['regex' => '/\b(?:repeat(?:ed)?|returning|frequent)\s*(?:customers?|clients?|users?|members?)\b/i', 'weight' => 35],
                    ['regex' => '/\b(?:customer|client)\s*retention\b/i', 'weight' => 30],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'repeat_customers', 'arg_map' => ['date_range' => '{date_range|all_time}', 'sort_by' => 'bookings']],
                ],
            ],

            'customer.lookup' => [
                'domain'   => 'customers',
                'priority' => 50,
                'patterns' => [
                    ['regex' => '/\b(?:find|search|lookup|get|show)\s+.*(?:customer|client|user)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:customer|client|pet\s*parent)\b/i', 'weight' => 10],
                    ['regex' => '/\b(?:is\s+\w+\s+(?:active|inactive|there|registered))\b/i', 'weight' => 15],
                    ['regex' => '/\b(?:check\s+(?:customer|client)|about\s+\w+\s+(?:customer|client))\b/i', 'weight' => 20],
                ],
                'exclusions' => [
                    '/\b(?:providers?|sps?|service\s*providers?)\b/i',
                    '/\b(?:repeat(?:ed)?|churned|retention|growth|wallet\s*audit|reconcil|collection|agreement|amount\s*received|payment)\b/i',
                    '/\b(?:how\s*many|count|number\s*of|total)\b/i',
                ],
                'required_entities' => ['search_name'],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'client_profile_lookup', 'arg_map' => ['client_search' => '{search_name}']],
                ],
            ],

            'customer.reconciliation' => [
                'domain'   => 'customers',
                'priority' => 15,
                'patterns' => [
                    ['regex' => '/\b(?:customer|client|user)\s*(?:wallet\s*)?reconcil\w*\b/i', 'weight' => 35],
                    ['regex' => '/\breconcil\w*\b/i', 'weight' => 10],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['search_name'],
                'guard' => 'customer_context',  // Only activate if customer context is detected
                'metrics'  => [
                    ['name' => 'client_wallet_reconciliation', 'arg_map' => ['client_search' => '{search_name|}']],
                ],
            ],

            // ─────────────────────────────────────────────
            // PROVIDER DOMAIN
            // ─────────────────────────────────────────────
            'provider.registrations' => [
                'domain'   => 'providers',
                'priority' => 15,
                'patterns' => [
                    ['regex' => '/\b(?:new\s*(?:service\s*)?providers?|providers?\s*registered|service\s*providers?\s*registered|providers?\s*onboarded|registered\s*(?:service\s*)?providers?|new\s*sps?|sp\s*registrations?|provider\s*registrations?|registered\s*today|registered\s*yesterday|registered\s*this\s*month|registered\s*this\s*week)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:how\s*many|count|number\s*of|total|breakdown|today|yesterday|this\s*month|last\s*month|this\s*week|new)\b/i', 'weight' => 10],
                ],
                'exclusions' => [
                    '/\b(?:clients?|customers?|pet\s*parents?|users?|bookings?|booking|orders?|order)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'city', 'service_type', 'status'],
                'requires_all_patterns' => true,
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => [
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                        'status'       => '{status|}',
                        'date_range'   => '{date_range|today}',
                    ]],
                ],
            ],

            'provider.headcount' => [
                'domain'   => 'providers',
                'priority' => 10,
                'patterns' => [
                    ['regex' => '/\b(?:how\s*many|count|number\s*of|total|breakdown|density|growth|trend|summary|pipeline|active|verified|available)\b/i', 'weight' => 10],
                    ['regex' => '/\b(?:walkers?|trainers?|groomers?|sitters?|boarders?|providers?|sps?|service\s*providers?)\b/i', 'weight' => 15],
                ],
                'exclusions' => [
                    '/\b(?:posts?|comments?|channels?|notifications?|bids?|bidding|earnings?|revenue|payouts?|withdrawals?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'city', 'status', 'date_range'],
                'requires_all_patterns' => true,  // Both pattern groups must match
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => [
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                        'status'       => '{status|}',
                        'date_range'   => '{date_range|all_time}',
                    ]],
                ],
            ],

            'provider.earnings' => [
                'domain'   => 'providers',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:provider(?:s|\'s)?\s*earnings?|sp\s*earnings?|providers?\s*revenue)\b/i', 'weight' => 35],
                    ['regex' => '/\bearnings?\s*of\s*providers?\b/i', 'weight' => 30],
                    ['regex' => '/\bhow\s*much\s*providers?\s*(?:earn|earned|earnings?)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:maximum|minimum|max|min|highest|lowest|average|avg)\s*(?:provider\s*)?earnings?\b/i', 'weight' => 25],
                    ['regex' => '/\btop\s*(?:earning|earners)\s*(?:providers?|walkers?|groomers?|trainers?|sps?)?\b/i', 'weight' => 25],
                    ['regex' => '/\bearned\s*(?:maximum|minimum|max|min|highest|lowest)\b/i', 'weight' => 25],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'service_type'],
                'metrics'  => [
                    ['name' => 'provider_revenue_overview', 'arg_map' => [
                        'date_range'   => '{date_range|all_time}',
                        'service_type' => '{service_type|}',
                    ]],
                ],
            ],

            'provider.reconciliation' => [
                'domain'   => 'providers',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\breconcil\w*\b/i', 'weight' => 20],
                    ['regex' => '/\bwallet\s*(?:calculation|audit|logic|mismatch|check|discrepancy)\b/i', 'weight' => 25],
                    ['regex' => '/\bapi\s*logic\b/i', 'weight' => 15],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['search_name'],
                'metrics'  => [
                    ['name' => 'provider_reconciliation_overview', 'arg_map' => ['provider_search' => '{search_name|}']],
                ],
            ],

            'provider.notifications' => [
                'domain'   => 'providers',
                'priority' => 20,
                'patterns' => [
                    ['regex' => '/\b(?:notifications?|push\s*notifications?|fcm|tokens?)\b/i', 'weight' => 15],
                    ['regex' => '/\b(?:providers?|sps?|bids?|bidding|quotations?|receiving|not\s*receiving|active|inactive|logs?|who|which)\b/i', 'weight' => 10],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'requires_all_patterns' => true,
                'metrics'  => [
                    ['name' => 'provider_notification_bidding_analysis', 'arg_map' => ['date_range' => 'all_time']],
                ],
            ],

            'provider.payout' => [
                'domain'   => 'providers',
                'priority' => 18,
                'patterns' => [
                    ['regex' => '/\b(?:payout\s*requests?|payout\s*balance|pending\s*payouts?|withdrawal\s*requests?|provider\s*payouts?|payouts?|withdrawals?|cashfree\s*(?:payout\s*)?balance)\b/i', 'weight' => 30],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'payout_balance_overview', 'arg_map' => ['date_range' => 'all_time']],
                ],
            ],

            'provider.lookup' => [
                'domain'   => 'providers',
                'priority' => 60,
                'patterns' => [
                    ['regex' => '/\b(?:service\s*providers?|providers?|sp|sps|partner|partners)\b/i', 'weight' => 12],
                    ['regex' => '/\b(?:about|who\s*is|profile|details)\b/i', 'weight' => 15],
                ],
                'exclusions' => [
                    '/\b(?:payout|withdrawal|wallet|cashfree|ledger|commission|revenue|sales|earnings|refund|tax|gst|amount|fee|fees|registration|cost|charge|collected)\b/i',
                    '/\b(?:how\s*many|count|number\s*of|total|breakdown|density|growth)\b/i',
                    '/\b(?:notifications?|push|fcm|bids?|bidding)\b/i',
                ],
                'required_entities' => ['search_name'],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => ['provider_search' => '{search_name}', 'date_range' => 'all_time']],
                ],
            ],

            // ─────────────────────────────────────────────
            // BOOKINGS DOMAIN
            // ─────────────────────────────────────────────
            'booking.active' => [
                'domain'   => 'bookings',
                'priority' => 15,
                'patterns' => [
                    ['regex' => '/\b(?:active|ongoing|confirmed|accepted|in\s*progress)\s*(?:bookings?|jobs?|service\s*requests?|orders?)\b/i', 'weight' => 35],
                    ['regex' => '/\b(?:bookings?|jobs?|service\s*requests?|orders?)\s*(?:active|ongoing|confirmed|accepted|in\s*progress)\b/i', 'weight' => 35],
                    ['regex' => '/\bactive\s*jobs?\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:total\s*)?value\s*of\s*active\s*(?:services?|bookings?|jobs?)\b/i', 'weight' => 45],
                    ['regex' => '/\bactive\s*(?:services?|bookings?|jobs?)\s*(?:total\s*)?value\b/i', 'weight' => 45],
                ],
                'exclusions' => [
                    '/\b(?:calls?|community|tickets?|sms|campaigns?|payouts?|withdrawals?|posts?|channels?|loyalty|points?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'city'],
                'metrics'  => [
                    ['name' => 'active_jobs', 'arg_map' => ['service_type' => '{service_type|}', 'city' => '{city|}']],
                    ['name' => 'client_wallets_overview', 'arg_map' => ['wallet_type' => 'escrow']],
                ],
            ],

            'booking.value' => [
                'domain'   => 'finance',
                'priority' => 30,
                'patterns' => [
                    ['regex' => '/\b(?:booking\s*value|gross\s*booking\s*value|gbv|gmv|booking\s*revenue|value\s*generated)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:total\s*booking\s*value|booking\s*value\s*generated)\b/i', 'weight' => 60],
                ],
                'exclusions' => [
                    '/\b(?:calls?|community|tickets?|sms|campaigns?|payouts?|withdrawals?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'service_type', 'city'],
                'metrics'  => [
                    ['name' => 'bookings_summary', 'arg_map' => [
                        'date_range'   => '{date_range|today}',
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                    ]],
                ],
            ],

            'booking.area' => [
                'domain'   => 'bookings',
                'priority' => 35,
                'patterns' => [
                    ['regex' => '/\b(?:which|what|top|highest|most|popular|breakdown|demand)\s+(?:areas?|localit(?:y|ies)|neighborhoods?|locations?|places?|zones?|pincodes?)\b/i', 'weight' => 55],
                    ['regex' => '/\b(?:bookings?|requests?|jobs?|orders?)\s*(?:by|in|across|per)\s+(?:area|areas|locality|localities|location|locations|neighborhoods?|zones?)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:areas?|localit(?:y|ies)|neighborhoods?)\s+(?:generated|produced|have|with|had)\s+(?:the\s+)?(?:highest|most|top|maximum)\b/i', 'weight' => 55],
                    ['regex' => '/\b(?:areas?|localit(?:y|ies)|neighborhoods?)\s+(?:with|having|for)\s+(?:the\s+)?(?:highest|most|maximum|top)\s+(?:number\s+of\s+)?(?:requests?|bookings?)\b/i', 'weight' => 55],
                    ['regex' => '/\b(?:high\s*demand|highest\s*demand)\s+(?:areas?|localit(?:y|ies)|locations?)\b/i', 'weight' => 45],
                ],
                'exclusions' => [
                    '/\b(?:calls?|community|tickets?|sms|campaigns?|payouts?|withdrawals?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'service_type', 'city'],
                'metrics'  => [
                    ['name' => 'bookings_by_area', 'arg_map' => [
                        'date_range'   => '{date_range|all_time}',
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                        'limit'        => 20,
                    ]],
                ],
            ],

            'booking.summary' => [
                'domain'   => 'bookings',
                'priority' => 10,
                'patterns' => [
                    ['regex' => '/\b(?:how\s*many\s*)?(?:bookings?|orders?|service\s*requests?)\s*(?:were\s*)?(?:created|placed|received|booked|made)\b/i', 'weight' => 45],
                    ['regex' => '/\b(?:how\s*many|count|number\s*of|total)\s+(?:bookings?|orders?|service\s*requests?)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:bookings?|jobs?|service\s*requests?|orders?|cancellations?|cancelled|canceled|halted)\b/i', 'weight' => 15],
                ],
                'exclusions' => [
                    '/\b(?:calls?|community|tickets?|sms|campaigns?|payouts?|withdrawals?|posts?|channels?|loyalty|points?)\b/i',
                    '/\b(?:which\s*areas?|which\s*localit|by\s*areas?|by\s*localit|top\s*areas?|highest\s*areas?|areas?\s*generated|areas?\s*with|areas?\s*having)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'status', 'service_type', 'city', 'booking_status'],
                'metrics'  => [
                    ['name' => 'bookings_summary', 'arg_map' => [
                        'date_range'   => '{date_range|all_time}',
                        'status'       => '{booking_status|}',
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                    ]],
                ],
            ],

            'booking.funnel' => [
                'domain'   => 'bookings',
                'priority' => 16,
                'patterns' => [
                    ['regex' => '/\b(?:funnel|conversion\s*funnel|completion\s*funnel)\b/i', 'weight' => 35],
                    ['regex' => '/\b(?:drop[-_\s]*offs?|dropoffs?|largest\s*drop[-_\s]*off)\b/i', 'weight' => 25],
                    ['regex' => '/\bwhy\s*(?:are\s*)?(?:clients?|customers?)?\s*(?:not\s*)?(?:completing|booking|paying)\b/i', 'weight' => 25],
                    ['regex' => '/\bwhy\s*bookings?\s*(?:halt|fail|cancel)\b/i', 'weight' => 25],
                    ['regex' => '/\bbottleneck\b/i', 'weight' => 20],
                    ['regex' => '/\b(?:dropoff_reasons|dropoff\s*reasons)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:cancellation|halt)\s*reasons?\b/i', 'weight' => 25],
                    ['regex' => '/\bwhy\s*(?:are|do)?\s*(?:bookings?|customers?|clients?|users?)?\s*(?:getting\s*)?(?:cancelled|canceled|cancelling|canceling)\b/i', 'weight' => 40],
                    ['regex' => '/\bwhy\s*(?:do|are)\s*bookings\s*cancelled\b/i', 'weight' => 25],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'date_range'],
                'metrics'  => 'funnel_dynamic',  // Special handling in MetricPlanner
            ],

            'booking.tracking' => [
                'domain'   => 'bookings',
                'priority' => 17,
                'patterns' => [
                    ['regex' => '/\b(?:untracked|not\s*tracked|without\s*tracking|missed\s*tracking)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:walks?|sessions?)\b.*\b(?:untracked|missed|tracking|short\s*walk|suspicious\s*walk)\b/i', 'weight' => 30],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'booking_status'],
                'metrics'  => [
                    ['name' => 'walking_tracking_analytics', 'arg_map' => [
                        'date_range'     => '{date_range|all_time}',
                        'status'         => 'untracked',
                        'booking_status' => '{booking_status|all}',
                    ]],
                ],
            ],

            // ─────────────────────────────────────────────
            // OPERATIONS DOMAIN
            // ─────────────────────────────────────────────
            'ops.approval_queue' => [
                'domain'   => 'operations',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:manager\s*approval|approval\s*(?:queue|pending)|pending\s*(?:manager\s*)?approval|awaiting\s*(?:manager\s*)?approval)\b/i', 'weight' => 35],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type'],
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => ['status' => 'approval', 'service_type' => '{service_type|}', 'date_range' => 'all_time']],
                ],
            ],

            'ops.fee_pending' => [
                'domain'   => 'operations',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:fee\s*pending|registration\s*fee\s*pending|pending\s*registration|unpaid\s*registration|pending\s*fee|unpaid\s*fee)\b/i', 'weight' => 35],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type'],
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => ['status' => 'pending_registration', 'service_type' => '{service_type|}', 'date_range' => 'all_time']],
                ],
            ],

            'ops.waiting_call' => [
                'domain'   => 'operations',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:waiting\s*(?:for)?\s*call|call\s*verification\s*queue)\b/i', 'weight' => 35],
                    ['regex' => '/\bpending\s*verification\b/i', 'weight' => 20],
                ],
                'exclusions' => [
                    '/\b(?:calls?\s*happened|calls?\s*done|calls?\s*conducted|interview)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['service_type'],
                'metrics'  => [
                    ['name' => 'provider_stats', 'arg_map' => ['status' => 'waiting for call', 'service_type' => '{service_type|}', 'date_range' => 'all_time']],
                ],
            ],

            'ops.verification_calls' => [
                'domain'   => 'operations',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\b(?:verifications?\s*calls?|verification\s*interviews?|verifier\s*calls?|telephonic\s*interviews?|executive\s*calls?|executive\s*call\s*records?|sp\s*verifications?\s*calls?)\b/i', 'weight' => 35],
                    ['regex' => '/\b(?:verifications?|verifier)\b.*\b(?:calls?|calling|happened|happen|done|conducted|interview|interviews)\b/i', 'weight' => 30],
                    ['regex' => '/\bverification_calls?\b/i', 'weight' => 35],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'call_verification_overview', 'arg_map' => ['date_range' => '{date_range|today}']],
                ],
            ],

            'ops.executive_payouts' => [
                'domain'   => 'operations',
                'priority' => 20,
                'patterns' => [
                    ['regex' => '/\b(?:executive\s*(?:payouts?|withdrawals?|wallets?|earnings?)|verification\s*executive\s*(?:payouts?|withdrawals?|wallets?|earnings?)|registration\s*executive\s*(?:payouts?|withdrawals?|wallets?|earnings?))\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:how\s*much\s+(?:did\s+)?[a-zA-Z]+\s+(?:withdraw|withdrawled|withdrawn|earn|earned))\b/i', 'weight' => 45],
                    ['regex' => '/\b(?:abhinaya|varalaxmi|tara\s*devi|divya\s*executive)\b.*\b(?:withdraw|withdrawled|withdrawn|payout|payouts|wallet|earnings?|earned)\b/i', 'weight' => 60],
                    ['regex' => '/\b(?:withdraw|withdrawled|withdrawn|payout|payouts|wallet|earnings?|earned)\b.*\b(?:abhinaya|varalaxmi|tara\s*devi|divya\s*executive)\b/i', 'weight' => 60],
                    ['regex' => '/\bexecutive\s*payouts?\s*also\s*there\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['search_name', 'date_range'],
                'metrics'  => [
                    ['name' => 'executive_payouts_overview', 'arg_map' => ['executive_search' => '{search_name|}', 'date_range' => '{date_range|all_time}']],
                ],
            ],

            'ops.verification_executives' => [
                'domain'   => 'operations',
                'priority' => 15,
                'patterns' => [
                    ['regex' => '/\b(?:verification\s*executives?|sp\s*verifiers?|verifier\s*performance|verification\s*team)\b/i', 'weight' => 40],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'verification_executives_overview', 'arg_map' => ['date_range' => '{date_range|30_days}']],
                ],
            ],

            'ops.call_quality' => [
                'domain'   => 'operations',
                'priority' => 5,
                'patterns' => [
                    ['regex' => '/\b(?:off[-_\s]*platform|off_platform|contact\s*sharing|outside\s*(?:the\s*)?platform|bypass\s*(?:the\s*)?platform)\b/i', 'weight' => 60],
                    ['regex' => '/\b(?:discussion|talk|deal|connect)\s+off[-_\s]*platform\b/i', 'weight' => 60],
                    ['regex' => '/\b(?:call\s*quality|call\s*qa|qa\s*score|flagged\s*calls?|abusive\s*calls?|call\s*sentiment)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'call_ai_quality_analytics', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            // ─────────────────────────────────────────────
            // ANALYTICS DOMAIN
            // ─────────────────────────────────────────────
            'analytics.community' => [
                'domain'   => 'analytics',
                'priority' => 18,
                'patterns' => [
                    ['regex' => '/\b(?:community\s*channels?|community\s*posts?|community\s*feeds?|community\s*groups?|community\s*overview|community\s*discussion|pet\s*community)\b/i', 'weight' => 35],
                    ['regex' => '/\bchannels?\s*list\b/i', 'weight' => 25],
                    ['regex' => '/\bhow\s*many\s*(?:community\s*)?channels?\b/i', 'weight' => 25],
                    ['regex' => '/\b(?:posts?|channels?)\b.*\b(?:community|admin|client|provider|sp|feed|discussions?|overview|how\s*many|total|breakdown)\b/i', 'weight' => 20],
                    ['regex' => '/\b(?:how\s*many|total)\s*posts?\b/i', 'weight' => 20],
                    ['regex' => '/\b(?:admin|client|provider|sp)\s*posts?\b/i', 'weight' => 20],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'community_overview', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'analytics.loyalty' => [
                'domain'   => 'analytics',
                'priority' => 18,
                'patterns' => [
                    ['regex' => '/\b(?:loyalty|loyalty\s*points?|rewards?|reward\s*points?|spin\s*wheel|loyalty\s*wallet)\b/i', 'weight' => 30],
                    ['regex' => '/\b(?:maximum|minimum|max|min|highest|lowest)\s*(?:loyalty\s*)?points?\b/i', 'weight' => 25],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'loyalty_overview', 'arg_map' => ['date_range' => 'all_time']],
                ],
            ],

            'analytics.events' => [
                'domain'   => 'analytics',
                'priority' => 20,
                'patterns' => [
                    ['regex' => '/\b(?:business\s*events?|platform\s*events?|events?\s*overview|event\s*stream|activity\s*stream|event\s*log)\b/i', 'weight' => 30],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'business_events_overview', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'payments.booking_transactions' => [
                'domain'   => 'analytics',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:client\s*payments?|booking\s*payments?|payments?\s*done\s*for\s*bookings?|payment\s*transactions?)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:how\s*many\s*(?:client\s*)?payments?|payment\s*performance|payment\s*attempts?)\b/i', 'weight' => 45],
                    ['regex' => '/\b(?:how\s*many\s*(?:are\s*)?success(?:ful)?[\s,]+fail(?:ed)?|success[\s,]+fail[\s,]+what\s*is\s*the\s*fail\s*reason)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:what\s*is\s*(?:the\s*)?fail\s*reason|failure\s*reasons?|why\s*(?:did\s*)?payments?\s*fail|failed\s*payments?)\b/i', 'weight' => 40],
                    ['regex' => '/\b(?:admin\.petsfolio\.in\/payments|finance\s*payments|payment\s*transactions\s*dashboard)\b/i', 'weight' => 50],
                ],
                'exclusions' => [
                    '/\b(?:payouts?|withdrawals?|cashfree\s*balance|executive\s*payouts?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'status'],
                'metrics'  => [
                    ['name' => 'booking_payments_overview', 'arg_map' => ['date_range' => '{date_range|all_time}', 'status_filter' => '{booking_status|}']],
                ],
            ],

            'revenue.overview' => [
                'domain'   => 'analytics',
                'priority' => 11,
                'patterns' => [
                    ['regex' => '/\b(?:what\s*is\s*(?:our\s*)?revenue|platform\s*revenue|gross\s*(?:revenue|booking|value)|gmv|total\s*revenue|revenue\s*overview|revenue\s*summary)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:how\s*much\s*(?:money|revenue|did\s*we\s*(?:make|get|collect|receive))|platform\s*earnings?|clean\s*commission)\b/i', 'weight' => 45],
                    ['regex' => '/\b(?:gmv\s*this\s*month|revenue\s*this\s*month|revenue\s*today|gmv\s*today)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:compare\s*.*revenue|revenue\s*by\s*service|highest\s*revenue\s*(?:locations?|cities)|which\s*(?:cities|locations?)\s*(?:generate|have|make)?\s*(?:the\s*)?highest\s*revenue)\b/i', 'weight' => 50],
                ],
                'exclusions' => [
                    '/\b(?:provider(?:s|\'s)?\s*earnings?|sp\s*earnings?|how\s*much\s*(?:did\s+)?[a-zA-Z]+\s+withdraw|client\s*payments?\s*done\s*for\s*bookings)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'service_type'],
                'metrics'  => [
                    ['name' => 'revenue_overview', 'arg_map' => ['date_range' => '{date_range|30_days}', 'service_type' => '{service_type|}']],
                ],
            ],

            'wallet.provider' => [
                'domain'   => 'operations',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:service\s*wallet|sp\s*service\s*wallet|sp\s*withdrawal\s*wallet|provider\s*withdrawal\s*wallet|amount\s*in\s*service\s*wallet|amount\s*in\s*withdrawal\s*wallet|provider\s*escrow)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'search_name'],
                'metrics'  => [
                    ['name' => 'sp_wallets_overview', 'arg_map' => ['wallet_type' => 'all', 'service_type' => '{service_type|}', 'provider_search' => '{search_name|}']],
                ],
            ],

            'wallet.client' => [
                'domain'   => 'operations',
                'priority' => 25,
                'patterns' => [
                    ['regex' => '/\b(?:client\s*withdrawal\s*wallet|client\s*refund\s*wallet|user\s*withdrawal\s*wallet|user\s*service\s*wallet|client\s*service\s*wallet|amount\s*in\s*(?:withdrawal|refund)\s*wallet|clients?\s*have\s*amount\s*in\s*(?:withdrawal|refund)?\s*wallet)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:clients?|users?)\b.*\b(?:withdrawal\s*wallet|refund\s*wallet|wallet)\b/i', 'weight' => 45],
                    ['regex' => '/\b(?:withdrawal\s*wallet|refund\s*wallet|wallet)\b.*\b(?:clients?|users?)\b/i', 'weight' => 45],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['search_name'],
                'metrics'  => [
                    ['name' => 'client_wallets_overview', 'arg_map' => ['wallet_type' => 'all', 'client_search' => '{search_name|}']],
                ],
            ],

            'revenue.registration_fee' => [
                'domain'   => 'revenue',
                'priority' => 20,
                'patterns' => [
                    ['regex' => '/\b(?:registration\s*fees?|sp\s*registration\s*fees?|verification\s*fees?|onboarding\s*fees?)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:amount\s*collected\s*(?:only\s*)?from\s*(?:service\s*)?providers?\s*(?:for\s*verification|for\s*registration)?)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:how\s*much\s*registration\s*fee|registration\s*amount\s*collected)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'service_type'],
                'metrics'  => [
                    ['name' => 'registration_fee', 'arg_map' => ['date_range' => '{date_range|all_time}', 'service_type' => '{service_type|}']],
                ],
            ],

            'ops.tickets' => [
                'domain'   => 'operations',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\b(?:support\s*tickets?|client\s*support\s*tickets?|sp\s*support\s*tickets?|active\s*or\s*pending\s*tickets?|open\s*tickets?|how\s*many\s*(?:.*)?tickets?\s*are\s*active)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'status'],
                'metrics'  => [
                    ['name' => 'support_tickets_overview', 'arg_map' => ['date_range' => '{date_range|30_days}']],
                ],
            ],

            'funnel.crm' => [
                'domain'   => 'analytics',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\b(?:crm\s*lead\s*conversion\s*rate|lead\s*conversion\s*rate|crm\s*conversion|crm\s*leads?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'conversion_rate', 'arg_map' => ['date_range' => '{date_range|this_month}']],
                ],
            ],

            'customer.churn' => [
                'domain'   => 'customers',
                'priority' => 11,
                'patterns' => [
                    ['regex' => '/\b(?:losing\s*customers?|customer\s*churn|churn\s*rate|churned\s*(?:customers?|clients?)|inactive\s*(?:clients?|customers?)|customers?\s*(?:are\s*)?inactive|inactive\s*or\s*churned|without\s*bookings?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'churned_inactive_customers', 'arg_map' => ['days' => 90]],
                ],
            ],

            'overview.executive' => [
                'domain'   => 'analytics',
                'priority' => 10,
                'patterns' => [
                    ['regex' => '/\b(?:new\s*to\s*petsfolio|executive\s*overview|executive\s*summary|business\s*health|platform\s*overview|high\s*level\s*(?:overview|summary|metrics)|kpi\s*summary|ceo\s*dashboard)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:what\s*metrics\s*(?:can|should)\s*i\s*(?:track|look\s*at)|give\s*me\s*an\s*overview\s*of\s*petsfolio)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'executive_summary', 'arg_map' => ['date_range' => '{date_range|30_days}']],
                ],
            ],

            'services.catalog' => [
                'domain'   => 'operations',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:what\s*services\s*(?:do\s*we\s*offer|are\s*available|does\s*petsfolio\s*(?:offer|provide))|services\s*and\s*packages|service\s*catalog|available\s*services|services\s*offered)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:packages?\s*overview|what\s*packages\s*(?:do\s*we\s*have|are\s*there))\b/i', 'weight' => 45],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'services_settings_overview', 'arg_map' => []],
                ],
            ],

            'onboarding.tutorials' => [
                'domain'   => 'operations',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\b(?:tutorial\s*videos?|tutorials?|training\s*videos?|onboarding\s*videos?|tutorials?\s*overview|how\s*many\s*tutorials?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'tutorials_overview', 'arg_map' => ['date_range' => 'all_time']],
                ],
            ],

            'onboarding.verification_questions' => [
                'domain'   => 'operations',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\b(?:verification\s*questions?|screening\s*questions?|interview\s*questions?|sp\s*verification\s*questions?|questions?\s*asked\s*(?:to|for)\s*(?:new\s*)?(?:service\s*)?providers?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type'],
                'metrics'  => [
                    ['name' => 'verification_questions_overview', 'arg_map' => ['service_type' => '{service_type|}']],
                ],
            ],

            'pets.demographics' => [
                'domain'   => 'analytics',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:how\s*many\s*pets?|total\s*pets?|registered\s*pets?|pets?\s*on\s*petsfolio|pets?\s*registered|pet\s*count|pet\s*demographics?)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:dogs?\s*(?:vs|versus|and)\s*cats?|cats?\s*(?:vs|versus|and)\s*dogs?|breakdown\s+of\s+dogs|species\s*breakdown|vaccinated\s*pets?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [
                    '/\b(?:walkers?|groomers?|trainers?|sitters?|boarders?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'pet_demographics', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'pets.breeds' => [
                'domain'   => 'analytics',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:dog\s*breeds?|breeds?\s*(?:overview|breakdown|list)|popular\s*(?:dog\s*)?breeds?|most\s*common\s*(?:dog\s*)?breeds?|which\s*breeds?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => [],
                'metrics'  => [
                    ['name' => 'dog_breeds_overview', 'arg_map' => []],
                ],
            ],

            'feedback.reviews' => [
                'domain'   => 'operations',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:customer\s*(?:reviews?|ratings?|feedback)|average\s*rating|feedback\s*and\s*reviews?|customer\s*satisfaction|how\s*are\s*reviews?|rating\s*breakdown)\b/i', 'weight' => 50],
                    ['regex' => '/\b(?:reviews?\s*for\s*(?:walking|grooming|training|boarding|sitting))\b/i', 'weight' => 45],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'feedback_and_reviews_overview', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'finance.refunds' => [
                'domain'   => 'analytics',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:refunds?|user\s*refunds?|client\s*refunds?|refunded\s*amount|how\s*much\s*refunded|refund\s*summary|refund\s*requests?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'user_refunds_summary', 'arg_map' => ['date_range' => '{date_range|30_days}']],
                ],
            ],

            'marketing.coupons' => [
                'domain'   => 'analytics',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:coupons?|promo\s*codes?|discount\s*codes?|active\s*coupons?|coupon\s*usage|discounts?\s*overview)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'coupons_overview', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'marketing.referrals' => [
                'domain'   => 'analytics',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:referrals?|referral\s*signups?|referral\s*codes?|users?\s*referred|referrals?\s*overview)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'referrals_overview', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'operations.tracking_analytics' => [
                'domain'   => 'operations',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:distance\s*walked|total\s*distance\s*tracked|tracking\s*analytics?|tracked\s*walks?|how\s*many\s*(?:walks?|walking\s*sessions?)\s*tracked|live\s*tracking(?:\s*stats|\s*statistics)?|tracking\s*statistics|dog\s*walking\s*tracking|walking\s*sessions?\s*(?:were\s*)?tracked)\b/i', 'weight' => 60],
                    ['regex' => '/\b(?:live\s*tracking|gps\s*tracking|tracked\s*with\s*gps)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'walking_tracking_analytics', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'operations.bidding' => [
                'domain'   => 'operations',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:bids?\s*(?:conversion|rate)|bid\s*conversion\s*rate|provider\s*bids?|provider\s*bidding|bids?\s*(?:are\s*)?submitted|how\s*many\s*bids?|average\s*bids?\s*per\s*booking|bidding\s*(?:analytics?|behavior|conversion|performance|patterns?|activity))\b/i', 'weight' => 60],
                    ['regex' => '/\b(?:bids?|bidding|quotations?)\b.*\b(?:conversion|submitted|performance|behavior)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'bids_conversion_analytics', 'arg_map' => ['date_range' => '{date_range|30_days}']],
                ],
            ],

            'provider.performance' => [
                'domain'   => 'providers',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:provider\s*performance|performance\s*scores?|highest\s*performance\s*scores?|completion\s*quality|at[\s-]risk\s*(?:service\s*)?providers?|providers?\s*with\s*(?:low|high)\s*performance)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'search_name'],
                'metrics'  => [
                    ['name' => 'provider_performance_score', 'arg_map' => [
                        'provider_id' => '{search_name|}',
                        'date_range'  => '{date_range|30_days}'
                    ]],
                ],
            ],

            'provider.cancellation' => [
                'domain'   => 'providers',
                'priority' => 35,
                'patterns' => [
                    ['regex' => '/\b(?:which\s*)?providers?\s*(?:have|with|had|generated)?\s*(?:the\s*)?(?:highest|most|maximum|worst|top)?\s*cancellation\s*rates?\b/i', 'weight' => 60],
                    ['regex' => '/\b(?:provider|sp)\s*cancellation\s*rates?\b/i', 'weight' => 50],
                    ['regex' => '/\bcancellation\s*rates?\s*(?:by|across|per|for)\s*providers?\b/i', 'weight' => 50],
                    ['regex' => '/\bwho\s*(?:are\s*the\s*)?providers?\s*cancelling\b/i', 'weight' => 45],
                    ['regex' => '/\bproviders?\s*with\s*(?:high|highest|frequent)\s*cancellations?\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'service_type', 'city'],
                'metrics'  => [
                    ['name' => 'provider_cancellation_rate', 'arg_map' => [
                        'date_range'   => '{date_range|all_time}',
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                        'limit'        => 15
                    ]],
                ],
            ],

            'finance.recovered' => [
                'domain'   => 'analytics',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:recovered\s*(?:transactions?|revenue|bookings?)|dropped\s*transactions?\s*(?:were\s*)?(?:successfully\s*)?recovered|how\s*many\s*dropped\s*transactions|transaction\s*recovery|recovered\s*amounts?)\b/i', 'weight' => 60],
                    ['regex' => '/\brecover\w*\b.*\b(?:transactions?|dropped|bookings?)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'recovered_transactions_overview', 'arg_map' => ['date_range' => '{date_range|all_time}']],
                ],
            ],

            'marketing.lead_sources' => [
                'domain'   => 'analytics',
                'priority' => 13,
                'patterns' => [
                    ['regex' => '/\b(?:lead\s*(?:acquisition\s*)?sources?|acquisition\s*sources?|best\s*conversion\s*rate\s*and\s*lowest\s*dropoff|lead\s*source\s*performance)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'lead_source_performance', 'arg_map' => ['date_range' => '{date_range|this_month}']],
                ],
            ],

            'operations.strikes' => [
                'domain'   => 'operations',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:disciplinary\s*strikes?|provider\s*strikes?|sp\s*strikes?|attendance\s*violations?|strikes?\s*(?:or|and)\s*attendance|attendance\s*(?:penalties?|warnings?))\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range', 'search_name'],
                'metrics'  => [
                    ['name' => 'provider_strikes_attendance', 'arg_map' => [
                        'date_range'      => '{date_range|30_days}',
                        'provider_search' => '{search_name|}'
                    ]],
                ],
            ],

            'operations.fraud_anomalies' => [
                'domain'   => 'operations',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:fraud\s*anomal(?:y|ies)|fraud\s*detection|completion\s*spikes?|abnormal\s*cancellation\s*patterns?|detect\s*(?:any\s*)?fraud|suspicious\s*anomal(?:y|ies))\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'fraud_anomaly_detection', 'arg_map' => ['date_range' => '{date_range|30_days}']],
                ],
            ],

            'operations.density' => [
                'domain'   => 'operations',
                'priority' => 12,
                'patterns' => [
                    ['regex' => '/\b(?:low\s*(?:provider\s*)?density|provider\s*shortages?|supply\s*shortages?|severe\s*provider\s*shortages?|deficit\s*areas?|sparse\s*coverage)\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'city', 'date_range'],
                'metrics'  => [
                    ['name' => 'low_provider_density_areas', 'arg_map' => [
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                        'date_range'   => '{date_range|30_days}'
                    ]],
                ],
            ],

            'operations.demand_supply' => [
                'domain'   => 'operations',
                'priority' => 14,
                'patterns' => [
                    ['regex' => '/\b(?:demand\s*(?:vs|versus|and)\s*supply|supply\s*(?:vs|versus|and)\s*demand|demand\s*supply\s*imbalance|inadequate\s*provider\s*supply|high\s*demand\s*(?:but|and)\s*low\s*supply|demand[-_\s]*(?:to|vs)?[-_\s]*provider\s*ratio|demand[-_\s]*supply\s*ratio|ratio\s*(?:for|in|by)\s*(?:each\s*)?(?:area|locality|city)|zero\s*providers?|no\s*(?:available\s*)?(?:service\s*)?providers?|zero\s*supply|demand\s*without\s*supply)\b/i', 'weight' => 75],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'city', 'date_range'],
                'metrics'  => [
                    ['name' => 'locality_demand_supply', 'arg_map' => [
                        'service_type' => '{service_type|}',
                        'city'         => '{city|}',
                        'date_range'   => '{date_range|30_days}'
                    ]],
                ],
            ],

            'operations.daily_management_matrix' => [
                'domain'   => 'operations',
                'priority' => 5,
                'patterns' => [
                    ['regex' => '/\bwhere\s*do\s*we\s*have\s*demand\s*today/i', 'weight' => 70],
                    ['regex' => '/\binsufficient\s*supply\b/i', 'weight' => 50],
                    ['regex' => '/\brevenue\s*at\s*risk\b/i', 'weight' => 60],
                    ['regex' => '/\bwho\s*owns\s*the\s*action\b/i', 'weight' => 60],
                    ['regex' => '/\bdaily\s*management\s*(?:question|matrix|table|action)\b/i', 'weight' => 60],
                    ['regex' => '/\bsupply\s*gap\b/i', 'weight' => 45],
                    ['regex' => '/\barea\s*[- ]\s*wise\s*business\s*gap/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['city', 'service_type', 'date_range'],
                'metrics'  => [
                    ['name' => 'daily_management_action_matrix', 'arg_map' => [
                        'date_range'   => '{date_range|today}',
                        'city'         => '{city|}',
                        'service_type' => '{service_type|}'
                    ]],
                ],
            ],

            'analytics.target_vs_actual' => [
                'domain'   => 'analytics',
                'priority' => 8,
                'patterns' => [
                    ['regex' => '/\btarget\s*(?:versus|vs)\s*actual\b/i', 'weight' => 60],
                    ['regex' => '/\bactual\s*(?:versus|vs)\s*target\b/i', 'weight' => 60],
                    ['regex' => '/\btoday\'?s?\s*target\b/i', 'weight' => 50],
                    ['regex' => '/\bachievement\s*percentage\b/i', 'weight' => 55],
                    ['regex' => '/\bkpis?\s*(?:are\s*)?below\s*target\b/i', 'weight' => 60],
                    ['regex' => '/\babove\s*or\s*below\s*(?:the\s*)?(?:monthly|quarterly)?\s*targets?\b/i', 'weight' => 55],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'target_vs_actual_performance', 'arg_map' => [
                        'date_range' => '{date_range|today}'
                    ]],
                ],
            ],

            'finance.net_revenue_waterfall' => [
                'domain'   => 'finance',
                'priority' => 8,
                'patterns' => [
                    ['regex' => '/\bnet\s*revenue\s*after\s*discounts\b/i', 'weight' => 65],
                    ['regex' => '/\bcontribution\s*margin\b/i', 'weight' => 60],
                    ['regex' => '/\bpayment\s*gateway\s*charges\b/i', 'weight' => 50],
                    ['regex' => '/\bwhich\s*services?,\s*cities?.*most\s*profitable\b/i', 'weight' => 55],
                    ['regex' => '/\brevenue\s*waterfall\b/i', 'weight' => 55],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['service_type', 'date_range'],
                'metrics'  => [
                    ['name' => 'net_revenue_waterfall', 'arg_map' => [
                        'date_range'   => '{date_range|30_days}',
                        'service_type' => '{service_type|}'
                    ]],
                ],
            ],

            'governance.kpi_catalog' => [
                'domain'   => 'governance',
                'priority' => 8,
                'patterns' => [
                    ['regex' => '/\b(?:first\s*)?dashboard\s*priorities\b/i', 'weight' => 60],
                    ['regex' => '/\bwho\s*owns\s*each\s*kpi\b/i', 'weight' => 60],
                    ['regex' => '/\bdata\s*refresh\s*frequency\b/i', 'weight' => 60],
                    ['regex' => '/\bsource\s*system\s*and\s*calculation\s*formula\b/i', 'weight' => 60],
                    ['regex' => '/\bmanagement\s*kpi\s*governance\b/i', 'weight' => 60],
                    ['regex' => '/\bdrill\s*down\s*from\s*company\b/i', 'weight' => 55],
                    ['regex' => '/\baction\s*log\b/i', 'weight' => 50],
                ],
                'exclusions' => [],
                'required_entities' => [],
                'optional_entities' => ['date_range'],
                'metrics'  => [
                    ['name' => 'management_kpi_governance', 'arg_map' => [
                        'date_range' => '{date_range|today}'
                    ]],
                ],
            ],

            // ─────────────────────────────────────────────
            // CONVERSATION DOMAIN (follow-ups)
            // ─────────────────────────────────────────────
            'conversation.followup' => [
                'domain'   => 'conversation',
                'priority' => 70,
                'patterns' => [
                    ['regex' => '/^(?:so\s+)?how\s*many\s*(?:are\s*there\s*)?(?:currently|today|total|in\s*total|now|active|new)?\??$/i', 'weight' => 20],
                    ['regex' => '/\bhow\s*many\s*are\s*there\s*(?:currently|now|today|total)?\b/i', 'weight' => 15],
                    ['regex' => '/\bhow\s*many\s*(?:currently|total)\b/i', 'weight' => 15],
                ],
                'exclusions' => [
                    '/\b(?:walkers?|trainers?|groomers?|sitters?|boarders?|providers?|sps?|clients?|customers?|users?|members?|jobs?|bookings?|requests?)\b/i',
                ],
                'required_entities' => [],
                'optional_entities' => [],
                'requires_history' => true,
                'metrics'  => 'followup_dynamic',  // Resolved at runtime from history
            ],
        ];
    }

    /**
     * Get a specific intent definition by ID.
     */
    public static function getIntent(string $intentId): ?array
    {
        return self::getIntents()[$intentId] ?? null;
    }

    /**
     * Get all intent IDs for a given domain.
     */
    public static function getIntentsByDomain(string $domain): array
    {
        $result = [];
        foreach (self::getIntents() as $id => $def) {
            if ($def['domain'] === $domain) {
                $result[$id] = $def;
            }
        }
        return $result;
    }

    /**
     * Get all valid intent IDs.
     */
    public static function getAllIntentIds(): array
    {
        return array_keys(self::getIntents());
    }

    /**
     * Get all valid domains.
     */
    public static function getDomains(): array
    {
        return ['identity', 'providers', 'customers', 'bookings', 'operations', 'analytics', 'finance', 'governance', 'conversation'];
    }
}
