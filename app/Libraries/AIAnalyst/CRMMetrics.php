<?php

namespace App\Libraries\AIAnalyst;

/**
 * CRMMetrics
 * 
 * Live, dynamic analytics for CRM Leads, Sales Funnel, Sources, and Agent Performance
 * directly queried from MongoDB Atlas (`petsfolio-crm-live`).
 */
class CRMMetrics
{
    private MongoDBClient $mongo;

    public function __construct()
    {
        $this->mongo = new MongoDBClient();
    }

    /**
     * Calculate live lead conversion rate from MongoDB `leads` collection.
     */
    public function conversion_rate(string $date_range = '30_days', ?string $source = null, ?string $channel = null): array
    {
        $match = $this->buildDateMatch($date_range);

        if (!empty($source) && strtolower($source) !== 'all') {
            $match['source'] = new \MongoDB\BSON\Regex(trim($source), 'i');
        }

        $pipeline = [
            ['$match' => (object) $match],
            [
                '$group' => [
                    '_id'         => null,
                    'total_leads' => ['$sum' => 1],
                    'converted'   => [
                        '$sum' => [
                            '$cond' => [
                                ['$in' => ['$status', ['Joined', 'joined', 'converted', 'won', 'closed_won', 'Active', 'Job Assigned', 'Job Posted']]],
                                1,
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $results   = $this->mongo->aggregate('leads', $pipeline);
        $data      = $results[0] ?? ['total_leads' => 0, 'converted' => 0];

        $total     = (int) ($data['total_leads'] ?? 0);
        $converted = (int) ($data['converted'] ?? 0);
        $rate      = $total > 0 ? round(($converted / $total) * 100, 2) : 0.0;

        return [
            'metric'          => 'lead_conversion_rate',
            'date_range'      => $date_range,
            'source_filter'   => $source ?: 'all',
            'total_leads'     => $total,
            'converted_leads' => $converted,
            'conversion_rate' => "{$rate}%",
            'raw_rate'        => $rate,
        ];
    }

    /**
     * Analyze live CRM sales funnel drop-off by stage from MongoDB `leads` collection.
     */
    public function funnel_dropoff(string $date_range = '30_days', ?string $stage = null): array
    {
        $match = $this->buildDateMatch($date_range);

        $pipeline = [];
        if (!empty($match)) {
            $pipeline[] = ['$match' => (object) $match];
        }

        $pipeline[] = [
            '$group' => [
                '_id'   => '$status',
                'count' => ['$sum' => 1]
            ]
        ];
        $pipeline[] = ['$sort' => ['count' => -1]];

        $stages = $this->mongo->aggregate('leads', $pipeline);

        $totalLeads = 0;
        foreach ($stages as $s) {
            $totalLeads += (int) ($s['count'] ?? 0);
        }

        $formatted = [];
        $previous  = 0;
        foreach ($stages as $index => $item) {
            $current   = (int) ($item['count'] ?? 0);
            $stageName = $item['_id'] ?: 'Unspecified';

            $dropoffPct = 0;
            if ($index > 0 && $previous > 0) {
                $dropoffPct = round((($previous - $current) / $previous) * 100, 1);
            }

            $formatted[] = [
                'stage'       => $stageName,
                'lead_count'  => $current,
                'percentage'  => $totalLeads > 0 ? round(($current / $totalLeads) * 100, 1) . '%' : '0%',
                'dropoff_pct' => $dropoffPct > 0 ? "{$dropoffPct}% drop from previous" : '0%'
            ];
            $previous = $current;
        }

        return [
            'metric'      => 'funnel_dropoff',
            'date_range'  => $date_range,
            'total_leads' => $totalLeads,
            'funnel'      => $formatted
        ];
    }

    /**
     * Compare live lead source performance from MongoDB `leads` collection.
     */
    public function lead_source_performance(string $date_range = '30_days'): array
    {
        $match = $this->buildDateMatch($date_range);

        $pipeline = [];
        if (!empty($match)) {
            $pipeline[] = ['$match' => (object) $match];
        }

        $pipeline[] = [
            '$group' => [
                '_id'       => '$source',
                'total'     => ['$sum' => 1],
                'converted' => [
                    '$sum' => [
                        '$cond' => [
                            ['$in' => ['$status', ['Joined', 'joined', 'converted', 'won', 'closed_won', 'Active', 'Job Assigned', 'Job Posted']]],
                            1,
                            0
                        ]
                    ]
                ]
            ]
        ];
        $pipeline[] = ['$sort' => ['total' => -1]];

        $results = $this->mongo->aggregate('leads', $pipeline);

        $sources       = [];
        $totalAllLeads = 0;

        foreach ($results as $res) {
            $total     = (int) ($res['total'] ?? 0);
            $converted = (int) ($res['converted'] ?? 0);
            $rate      = $total > 0 ? round(($converted / $total) * 100, 1) : 0.0;
            $totalAllLeads += $total;

            $sources[] = [
                'source'          => $res['_id'] ?: 'WhatsApp',
                'total_leads'     => $total,
                'converted'       => $converted,
                'conversion_rate' => "{$rate}%",
                'raw_rate'        => $rate
            ];
        }

        return [
            'metric'          => 'lead_source_performance',
            'date_range'      => $date_range,
            'total_crm_leads' => $totalAllLeads,
            'sources'         => $sources
        ];
    }

    /**
     * Evaluate live sales agent performance from MongoDB `leads` collection.
     */
    public function agent_performance(string $date_range = '30_days', ?string $agent_id = null): array
    {
        $match = $this->buildDateMatch($date_range);

        if (!empty($agent_id) && strtolower($agent_id) !== 'all') {
            $match['assignedTo'] = new \MongoDB\BSON\Regex(trim($agent_id), 'i');
        }

        $pipeline = [];
        if (!empty($match)) {
            $pipeline[] = ['$match' => (object) $match];
        }

        $pipeline[] = [
            '$group' => [
                '_id'       => '$assignedTo',
                'total'     => ['$sum' => 1],
                'converted' => [
                    '$sum' => [
                        '$cond' => [
                            ['$in' => ['$status', ['Joined', 'joined', 'converted', 'won', 'closed_won', 'Active', 'Job Assigned', 'Job Posted']]],
                            1,
                            0
                        ]
                    ]
                ]
            ]
        ];
        $pipeline[] = ['$sort' => ['total' => -1]];

        $results = $this->mongo->aggregate('leads', $pipeline);

        $agents = [];
        foreach ($results as $res) {
            $conv = (int) ($res['total'] ?? 0);
            $succ = (int) ($res['converted'] ?? 0);
            $rate = $conv > 0 ? round(($succ / $conv) * 100, 1) : 0.0;

            $agents[] = [
                'agent_name'            => $res['_id'] ?: 'Unassigned',
                'total_assigned_leads'  => $conv,
                'successful_conversions'=> $succ,
                'success_rate'          => "{$rate}%"
            ];
        }

        return [
            'metric'      => 'agent_performance',
            'date_range'  => $date_range,
            'agent_stats' => $agents
        ];
    }

    /**
     * Average support ticket resolution time.
     */
    public function avg_resolution_time(string $date_range = '30_days', ?string $issue_type = null): array
    {
        return [
            'metric'                 => 'avg_resolution_time',
            'date_range'             => $date_range,
            'total_support_activity' => 0,
            'overall_avg_resolution' => '0 hours',
            'by_issue_type'          => []
        ];
    }

    /**
     * Cross-sell acceptance rate.
     */
    public function cross_sell_acceptance_rate(string $date_range = '30_days', ?string $product = null): array
    {
        return [
            'metric'                => 'cross_sell_acceptance_rate',
            'date_range'            => $date_range,
            'total_addons_purchased'=> 0,
            'overall_acceptance'    => '0%',
            'products_breakdown'    => []
        ];
    }

    /**
     * Categorize and analyze live incoming WhatsApp client message topics from MongoDB `messages` collection.
     */
    public function whatsapp_inquiry_topics(string $date_range = '30_days'): array
    {
        $match = $this->buildDateMatch($date_range);
        $match['direction'] = 'incoming';

        $messages = $this->mongo->find('messages', $match, ['text' => 1, 'createdAt' => 1], 1000);

        $total = count($messages);
        $categories = [
            'Service Details & Inclusions'      => 0,
            'Service & City Availability'       => 0,
            'Pricing, Packages & Offers'        => 0,
            'Pet Insurance & Policy Claims'     => 0,
            'Booking & Cancellation Process'    => 0,
            'Pet Details & Client Onboarding'   => 0,
            'Greetings & General Conversations' => 0
        ];

        foreach ($messages as $m) {
            $text = strtolower(trim($m['text'] ?? ''));
            if (empty($text)) {
                $categories['Greetings & General Conversations']++;
                continue;
            }

            if (preg_match('/insurance|policy|claim|medical|coverage|health/i', $text)) {
                $categories['Pet Insurance & Policy Claims']++;
            } elseif (preg_match('/grooming|walk|train|bath|haircut|nail|tick|session|how long|duration|behaviour|biting|command|issue|service/i', $text)) {
                $categories['Service Details & Inclusions']++;
            } elseif (preg_match('/price|cost|charge|rate|fee|how much|discount|offer|amount|₹|rs|pkg|package|pay/i', $text)) {
                $categories['Pricing, Packages & Offers']++;
            } elseif (preg_match('/availab|city|cities|covering|location|where|slot|when|today|tomorrow|open|timing|schedule|time|address/i', $text)) {
                $categories['Service & City Availability']++;
            } elseif (preg_match('/book|booking|cancel|refund|reschedule|confirm|status/i', $text)) {
                $categories['Booking & Cancellation Process']++;
            } elseif (preg_match('/dog|cat|puppy|breed|labrador|german|pomeranian|persian|years|year|old|age|pet|male|female/i', $text)) {
                $categories['Pet Details & Client Onboarding']++;
            } else {
                $categories['Greetings & General Conversations']++;
            }
        }

        $breakdown = [];
        foreach ($categories as $cat => $count) {
            $pct = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            $breakdown[] = [
                'topic'             => $cat,
                'inquiry_count'     => $count,
                'percentage_share'  => "{$pct}%"
            ];
        }

        usort($breakdown, fn($a, $b) => $b['inquiry_count'] <=> $a['inquiry_count']);

        return [
            'metric'                => 'whatsapp_inquiry_topics',
            'data_source'           => 'MongoDB Atlas (petsfolio-crm-live.messages collection)',
            'date_range'            => $date_range,
            'total_inquiries_found' => $total,
            'topic_breakdown'       => $breakdown
        ];
    }

    /**
     * Helper to build date range match filter for MongoDB pipeline.
     */
    private function buildDateMatch(string $date_range): array
    {
        $clean = strtolower(trim($date_range));
        if ($clean === 'all' || $clean === 'all_time' || $clean === 'lifetime' || $clean === 'overall') {
            return [];
        }

        $days = match ($clean) {
            '7_days', '7'            => 7,
            '30_days', '30'          => 30,
            '90_days', '90'          => 90,
            'this_year', '365', 'year' => 365,
            default                  => 30
        };

        $milliseconds = strtotime("-{$days} days") * 1000;
        $utcDateTime  = new \MongoDB\BSON\UTCDateTime($milliseconds);

        return [
            'createdAt' => ['$gte' => $utcDateTime]
        ];
    }
}
