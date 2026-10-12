<?php

namespace App\Services;

use App\Models\AnalyticsEventModel;
use App\Models\AnalyticsFunnelModel;
use App\Models\AnalyticsSessionModel;

class FunnelAnalyticsService
{
    protected AnalyticsEventModel $eventModel;
    protected AnalyticsFunnelModel $funnelModel;
    protected AnalyticsSessionModel $sessionModel;

    public function __construct()
    {
        $this->eventModel   = new AnalyticsEventModel();
        $this->funnelModel  = new AnalyticsFunnelModel();
        $this->sessionModel = new AnalyticsSessionModel();
        $this->funnelModel->ensureTables();
    }

    /**
     * Calculate funnel metrics given a funnel definition and filter criteria
     */
    public function calculateFunnel(array $funnel, array $filters = []): array
    {
        $stepsRaw = is_string($funnel['steps']) ? json_decode($funnel['steps'], true) : $funnel['steps'];
        if (empty($stepsRaw) || !is_array($stepsRaw)) {
            return [
                'steps' => [],
                'kpis'  => [
                    'total_entrants'          => 0,
                    'total_completed'         => 0,
                    'overall_conversion_rate' => 0,
                    'biggest_dropoff_step'    => null,
                    'total_events'            => 0,
                ],
                'chart' => ['labels' => [], 'data' => [], 'dropoff' => []],
            ];
        }

        $stepEvents = [];
        foreach ($stepsRaw as $idx => $s) {
            $stepEvents[$idx] = [
                'step_index' => $idx,
                'step_key'   => $s['step_key'] ?? ('step_' . ($idx + 1)),
                'name'       => $s['name'] ?? ('Step ' . ($idx + 1)),
                'event_name' => $s['event_name'] ?? '',
                'screen'     => $s['screen'] ?? '',
                'icon'       => $s['icon'] ?? 'fas fa-arrow-right',
            ];
        }

        $eventNames = array_filter(array_column($stepEvents, 'event_name'));
        if (empty($eventNames)) {
            return [
                'steps' => [],
                'kpis'  => ['total_entrants' => 0, 'total_completed' => 0, 'overall_conversion_rate' => 0, 'biggest_dropoff_step' => null, 'total_events' => 0],
                'chart' => ['labels' => [], 'data' => [], 'dropoff' => []]
            ];
        }

        // Build filtered query to retrieve events
        $builder = $this->eventModel->buildFilteredQuery($filters);
        $builder->whereIn('event_name', $eventNames);
        $builder->orderBy('created_at', 'ASC');
        $events = $builder->get()->getResultArray();

        $totalFilteredEvents = count($events);

        // Group events by session_id (and fallback user_id)
        $sessionJourneys = [];
        foreach ($events as $e) {
            $sessKey = !empty($e['session_id']) ? $e['session_id'] : ('user_' . ($e['user_id'] ?? 'anon'));
            if (!isset($sessionJourneys[$sessKey])) {
                $sessionJourneys[$sessKey] = [
                    'session_id' => $e['session_id'] ?? $sessKey,
                    'user_id'    => $e['user_id'] ?? null,
                    'platform'   => $e['platform'] ?? 'unknown',
                    'city'       => $e['city'] ?? '',
                    'events'     => [],
                ];
            }
            $sessionJourneys[$sessKey]['events'][] = [
                'event_name' => $e['event_name'],
                'created_at' => strtotime($e['created_at']),
            ];
        }

        // Determine step progression for each session
        $stepReachedSessions = [];
        $stepTransitionTimes = []; // step_index => array of seconds
        $totalSteps = count($stepEvents);

        for ($i = 0; $i < $totalSteps; $i++) {
            $stepReachedSessions[$i] = [];
            $stepTransitionTimes[$i] = [];
        }

        foreach ($sessionJourneys as $sessKey => $journey) {
            $eventsInJourney = $journey['events'];
            $currentStepToMatch = 0;
            $stepTimestamps = [];

            foreach ($eventsInJourney as $ev) {
                if ($currentStepToMatch < $totalSteps) {
                    if ($ev['event_name'] === $stepEvents[$currentStepToMatch]['event_name']) {
                        $stepReachedSessions[$currentStepToMatch][$sessKey] = $journey['user_id'] ?: $sessKey;
                        $stepTimestamps[$currentStepToMatch] = $ev['created_at'];
                        
                        // Transition time from previous step
                        if ($currentStepToMatch > 0 && isset($stepTimestamps[$currentStepToMatch - 1])) {
                            $diffSec = $ev['created_at'] - $stepTimestamps[$currentStepToMatch - 1];
                            if ($diffSec >= 0 && $diffSec <= 86400) { // cap at 24h for anomaly exclusion
                                $stepTransitionTimes[$currentStepToMatch - 1][] = $diffSec;
                            }
                        }
                        $currentStepToMatch++;
                    }
                }
            }
        }

        // Build metrics for each step
        $metricsSteps = [];
        $step1Count = count($stepReachedSessions[0] ?? []);
        $biggestDropoffPct = -1;
        $biggestDropoffStep = null;

        for ($i = 0; $i < $totalSteps; $i++) {
            $reached = count($stepReachedSessions[$i]);
            $isLastStep = ($i === $totalSteps - 1);
            
            $completed = $isLastStep ? $reached : count($stepReachedSessions[$i + 1]);
            $dropped = $isLastStep ? 0 : max(0, $reached - $completed);

            $dropoffPct = ($reached > 0 && !$isLastStep) ? round(($dropped / $reached) * 100, 1) : 0;
            $nextConversionPct = ($reached > 0 && !$isLastStep) ? round(($completed / $reached) * 100, 1) : ($reached > 0 ? 100 : 0);
            $overallConversionPct = ($step1Count > 0) ? round(($reached / $step1Count) * 100, 1) : 0;

            // Average transition time
            $avgTimeFormatted = '—';
            if (!$isLastStep && !empty($stepTransitionTimes[$i])) {
                $avgSec = round(array_sum($stepTransitionTimes[$i]) / count($stepTransitionTimes[$i]));
                $avgTimeFormatted = $this->formatDuration($avgSec);
            }

            if (!$isLastStep && $dropoffPct > $biggestDropoffPct && $reached > 0) {
                $biggestDropoffPct = $dropoffPct;
                $biggestDropoffStep = [
                    'name'        => $stepEvents[$i]['name'],
                    'dropoff_pct' => $dropoffPct,
                    'dropped'     => $dropped,
                    'next_step'   => $stepEvents[$i + 1]['name']
                ];
            }

            $metricsSteps[] = [
                'step_index'             => $i,
                'step_key'               => $stepEvents[$i]['step_key'],
                'name'                   => $stepEvents[$i]['name'],
                'event_name'             => $stepEvents[$i]['event_name'],
                'screen'                 => $stepEvents[$i]['screen'],
                'icon'                   => $stepEvents[$i]['icon'],
                'users_reached'          => $reached,
                'users_completed'        => $completed,
                'dropped_users'          => $dropped,
                'dropoff_pct'            => $dropoffPct,
                'next_conversion_pct'    => $nextConversionPct,
                'overall_conversion_pct' => $overallConversionPct,
                'avg_time'               => $avgTimeFormatted,
            ];
        }

        $totalCompleted = $totalSteps > 0 ? count($stepReachedSessions[$totalSteps - 1]) : 0;
        $overallConversionRate = ($step1Count > 0) ? round(($totalCompleted / $step1Count) * 100, 1) : 0;

        return [
            'steps' => $metricsSteps,
            'kpis'  => [
                'total_entrants'          => $step1Count,
                'total_completed'         => $totalCompleted,
                'overall_conversion_rate' => $overallConversionRate,
                'biggest_dropoff_step'    => $biggestDropoffStep,
                'total_events'            => $totalFilteredEvents,
                'total_sessions'          => count($sessionJourneys),
            ],
            'chart' => [
                'labels'     => array_column($metricsSteps, 'name'),
                'data'       => array_column($metricsSteps, 'users_reached'),
                'dropoff'    => array_column($metricsSteps, 'dropoff_pct'),
                'conversion' => array_column($metricsSteps, 'next_conversion_pct'),
            ],
        ];
    }

    /**
     * Retrieve problem & rage signals (errors, rage clicks, validation issues, drop-off exits)
     */
    public function getProblemSignals(array $filters = []): array
    {
        $problemEvents = [
            'validation_error',
            'payment_failed',
            'api_error',
            'location_permission_denied',
            'back_clicked',
            'retry_clicked',
            'session_timeout',
            'error_displayed',
        ];

        $builder = $this->eventModel->buildFilteredQuery($filters);
        $builder->whereIn('event_name', $problemEvents);
        $rawSignals = $builder->get()->getResultArray();

        $totalProblemSignals = count($rawSignals);

        // Group by event_name and screen_name
        $grouped = [];
        $sessionsWithError = [];

        foreach ($rawSignals as $sig) {
            $sessId = $sig['session_id'];
            $ev = $sig['event_name'];
            $scr = !empty($sig['screen_name']) ? $sig['screen_name'] : 'Unknown Screen';
            $key = $ev . '___' . $scr;

            $meta = [];
            if (!empty($sig['metadata'])) {
                $meta = is_string($sig['metadata']) ? json_decode($sig['metadata'], true) : $sig['metadata'];
            }
            $errMsg = $meta['error'] ?? $meta['message'] ?? $meta['reason'] ?? 'Action / Validation Failure';

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'event_name'     => $ev,
                    'screen_name'    => $scr,
                    'error_message'  => $errMsg,
                    'count'          => 0,
                    'sessions'       => [],
                    'last_occurred'  => $sig['created_at'],
                    'severity'       => $this->getSignalSeverity($ev),
                ];
            }
            $grouped[$key]['count']++;
            $grouped[$key]['sessions'][$sessId] = true;
            $sessionsWithError[$sessId] = true;
        }

        // Calculate drop-off exit correlation:
        // How many of these sessions terminated without reaching 'booking_confirmed' or 'payment_success'
        $completedSessions = [];
        if (!empty($sessionsWithError)) {
            $db = \Config\Database::connect();
            $sessKeys = array_keys($sessionsWithError);
            
            // Chunk query to avoid huge SQL whereIn
            $chunks = array_chunk($sessKeys, 200);
            foreach ($chunks as $chunk) {
                $successRows = $db->table('analytics_events')
                    ->select('DISTINCT(session_id)')
                    ->whereIn('session_id', $chunk)
                    ->whereIn('event_name', ['booking_confirmed', 'payment_success', 'sp_activated'])
                    ->get()->getResultArray();
                foreach ($successRows as $sr) {
                    $completedSessions[$sr['session_id']] = true;
                }
            }
        }

        $signalList = [];
        foreach ($grouped as $g) {
            $totalSess = count($g['sessions']);
            $droppedCount = 0;
            foreach (array_keys($g['sessions']) as $sId) {
                if (!isset($completedSessions[$sId])) {
                    $droppedCount++;
                }
            }
            $exitRate = $totalSess > 0 ? round(($droppedCount / $totalSess) * 100, 1) : 0;
            
            $g['unique_sessions'] = $totalSess;
            $g['dropped_count']   = $droppedCount;
            $g['exit_rate_pct']   = $exitRate;
            unset($g['sessions']);
            $signalList[] = $g;
        }

        // Sort by impact: count * exit_rate
        usort($signalList, function ($a, $b) {
            $impactA = $a['count'] * ($a['exit_rate_pct'] / 100);
            $impactB = $b['count'] * ($b['exit_rate_pct'] / 100);
            return $impactB <=> $impactA;
        });

        // Event type summary breakdown
        $typeBreakdown = [];
        foreach ($signalList as $item) {
            $ev = $item['event_name'];
            $typeBreakdown[$ev] = ($typeBreakdown[$ev] ?? 0) + $item['count'];
        }

        return [
            'total_signals'  => $totalProblemSignals,
            'unique_issues'  => count($signalList),
            'signals'        => $signalList,
            'type_breakdown' => $typeBreakdown,
        ];
    }

    /**
     * Get users / sessions who dropped off at a specific funnel step
     */
    public function getDropoffUsers(string $funnelKey, string $stepKey, array $filters = []): array
    {
        $funnel = $this->funnelModel->getByKey($funnelKey);
        if (!$funnel) return [];

        $steps = is_string($funnel['steps']) ? json_decode($funnel['steps'], true) : $funnel['steps'];
        $targetIndex = null;
        foreach ($steps as $idx => $s) {
            if (($s['step_key'] ?? '') === $stepKey || ($s['event_name'] ?? '') === $stepKey) {
                $targetIndex = $idx;
                break;
            }
        }

        if ($targetIndex === null || $targetIndex >= count($steps) - 1) {
            return []; // Last step has no subsequent drop-off
        }

        $currentEvent = $steps[$targetIndex]['event_name'];
        $nextEvent = $steps[$targetIndex + 1]['event_name'];

        $db = \Config\Database::connect();

        // 1. Sessions reaching currentEvent
        $currBuilder = $this->eventModel->buildFilteredQuery($filters);
        $currBuilder->select('session_id, user_id, platform, device, app_version, city, created_at, metadata');
        $currBuilder->where('event_name', $currentEvent);
        $currRows = $currBuilder->get()->getResultArray();

        $reachedSessions = [];
        foreach ($currRows as $r) {
            $reachedSessions[$r['session_id']] = $r;
        }

        if (empty($reachedSessions)) {
            return [];
        }

        // 2. Filter out sessions that reached nextEvent
        $sessIds = array_keys($reachedSessions);
        $chunks = array_chunk($sessIds, 200);
        $nextSessions = [];
        foreach ($chunks as $chunk) {
            $nextRows = $db->table('analytics_events')
                ->select('DISTINCT(session_id)')
                ->whereIn('session_id', $chunk)
                ->where('event_name', $nextEvent)
                ->get()->getResultArray();
            foreach ($nextRows as $nr) {
                $nextSessions[$nr['session_id']] = true;
            }
        }

        $droppedSessions = [];
        foreach ($reachedSessions as $sId => $row) {
            if (!isset($nextSessions[$sId])) {
                $droppedSessions[] = $row;
            }
        }

        // 3. Enrich with user info from users table if available
        $userIds = array_filter(array_column($droppedSessions, 'user_id'));
        $usersMap = [];
        if (!empty($userIds) && $db->tableExists('users')) {
            $uRows = $db->table('users')
                ->select('id, name, phone, email, city')
                ->whereIn('id', array_values(array_unique($userIds)))
                ->get()->getResultArray();
            foreach ($uRows as $u) {
                $usersMap[$u['id']] = $u;
            }
        }

        $enriched = [];
        foreach ($droppedSessions as $d) {
            $u = $usersMap[$d['user_id']] ?? null;
            $enriched[] = [
                'session_id'     => $d['session_id'],
                'user_id'        => $d['user_id'],
                'user_name'      => $u['name'] ?? 'Guest / Unregistered User',
                'user_phone'     => $u['phone'] ?? '—',
                'user_email'     => $u['email'] ?? '—',
                'platform'       => ucfirst($d['platform']),
                'device'         => $d['device'] ?? 'Mobile Device',
                'app_version'    => $d['app_version'] ?? 'v2.4.1',
                'city'           => !empty($d['city']) ? $d['city'] : ($u['city'] ?? 'Hyderabad'),
                'last_active_at' => $d['created_at'],
                'step_name'      => $steps[$targetIndex]['name'],
                'next_step_name' => $steps[$targetIndex + 1]['name'],
            ];
        }

        // Sort latest active first
        usort($enriched, function ($a, $b) {
            return strcmp($b['last_active_at'], $a['last_active_at']);
        });

        return array_slice($enriched, 0, 50); // Top 50 dropped users
    }

    /**
     * Generate rich, realistic sample telemetry data across dates, platforms, and services
     */
    public function generateSampleData(int $days = 14, int $totalJourneys = 180): array
    {
        $db = \Config\Database::connect();
        $this->funnelModel->ensureTables();

        $funnel = $this->funnelModel->getByKey('customer_booking');
        if (!$funnel) {
            return ['status' => 'error', 'message' => 'Default customer_booking funnel not found.'];
        }

        $steps = json_decode($funnel['steps'], true);
        $platforms = ['android', 'android', 'android', 'ios', 'ios', 'web'];
        $devices = [
            'android' => ['Samsung Galaxy S23', 'Xiaomi Redmi Note 12', 'OnePlus 11R', 'Pixel 7', 'Vivo V27'],
            'ios'     => ['iPhone 15 Pro', 'iPhone 14', 'iPhone 13', 'iPhone 12'],
            'web'     => ['Chrome / Mac OS', 'Chrome / Windows 11', 'Safari / iOS Web'],
        ];
        $cities = ['Hyderabad', 'Hyderabad', 'Bangalore', 'Bangalore', 'Mumbai', 'Chennai'];
        $services = [
            ['id' => 4, 'name' => 'Walking'],
            ['id' => 2, 'name' => 'Grooming'],
            ['id' => 3, 'name' => 'Training'],
            ['id' => 1, 'name' => 'Boarding'],
        ];
        $paymentMethods = ['upi', 'upi', 'upi', 'card', 'netbanking', 'wallet'];

        // Get actual user IDs from users table if any
        $realUsers = [];
        if ($db->tableExists('users')) {
            $realUsers = $db->table('users')->select('id, name, phone, city')->limit(50)->get()->getResultArray();
        }

        $batchEvents = [];
        $now = time();

        $makeEvent = function (array $data) {
            return [
                'session_id'       => $data['session_id'] ?? '',
                'user_id'          => $data['user_id'] ?? null,
                'platform'         => $data['platform'] ?? 'unknown',
                'device'           => $data['device'] ?? null,
                'app_version'      => $data['app_version'] ?? null,
                'event_name'       => $data['event_name'] ?? '',
                'screen_name'      => $data['screen_name'] ?? null,
                'booking_id'       => $data['booking_id'] ?? null,
                'service_id'       => $data['service_id'] ?? null,
                'service_category' => $data['service_category'] ?? null,
                'provider_id'      => $data['provider_id'] ?? null,
                'city'             => $data['city'] ?? null,
                'payment_method'   => $data['payment_method'] ?? null,
                'campaign_source'  => $data['campaign_source'] ?? null,
                'is_new_user'      => !empty($data['is_new_user']) ? 1 : 0,
                'metadata'         => isset($data['metadata']) ? (is_array($data['metadata']) ? json_encode($data['metadata'], JSON_UNESCAPED_UNICODE) : $data['metadata']) : null,
                'ip_address'       => $data['ip_address'] ?? null,
                'user_agent'       => $data['user_agent'] ?? null,
                'created_at'       => $data['created_at'] ?? date('Y-m-d H:i:s'),
            ];
        };

        for ($j = 0; $j < $totalJourneys; $j++) {
            // Pick a random day in the past $days
            $dayOffset = rand(0, $days - 1);
            $journeyStartTime = $now - ($dayOffset * 86400) + rand(0, 43200);

            $platform = $platforms[array_rand($platforms)];
            $deviceList = $devices[$platform];
            $device = $deviceList[array_rand($deviceList)];
            $appVersion = $platform === 'web' ? 'v1.0' : ('v2.' . rand(3, 5) . '.' . rand(0, 9));
            $city = $cities[array_rand($cities)];
            $service = $services[array_rand($services)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $isNewUser = rand(1, 100) <= 60 ? 1 : 0;
            $sessionId = 'sess_' . bin2hex(random_bytes(8)) . '_' . ($journeyStartTime);

            $userId = null;
            if (!$isNewUser && !empty($realUsers)) {
                $userObj = $realUsers[array_rand($realUsers)];
                $userId = $userObj['id'];
                if (!empty($userObj['city'])) $city = $userObj['city'];
            }

            // Decide drop-off stage for this journey based on realistic funnel drop rates:
            // Reg (100%) -> OTP (88%) -> Profile (80%) -> Pet (70%) -> Svc Browse (62%) -> Svc Select (55%)
            // -> Provider (48%) -> Booking Details (38%) -> Checkout (30%) -> Payment (24%) -> Confirmed (22%)
            $randPct = rand(1, 100);
            $maxStepReached = 0;
            if ($randPct <= 22) {
                $maxStepReached = 11; // Booking Confirmed
            } elseif ($randPct <= 28) {
                $maxStepReached = 10; // Dropped at Payment Success (Payment Failed)
            } elseif ($randPct <= 35) {
                $maxStepReached = 9;  // Dropped after Payment Initiated
            } elseif ($randPct <= 45) {
                $maxStepReached = 8;  // Dropped at Checkout
            } elseif ($randPct <= 58) {
                $maxStepReached = 7;  // Dropped at Booking Details (e.g. address validation)
            } elseif ($randPct <= 68) {
                $maxStepReached = 6;  // Dropped at Provider Select
            } elseif ($randPct <= 76) {
                $maxStepReached = 5;  // Dropped at Service Selected
            } elseif ($randPct <= 84) {
                $maxStepReached = 4;  // Dropped at Service Browse
            } elseif ($randPct <= 90) {
                $maxStepReached = 3;  // Dropped at Pet Added
            } elseif ($randPct <= 95) {
                $maxStepReached = 2;  // Dropped at Profile
            } else {
                $maxStepReached = 1;  // Dropped at OTP
            }

            $currentTime = $journeyStartTime;

            for ($sIdx = 0; $sIdx <= $maxStepReached && $sIdx < count($steps); $sIdx++) {
                $step = $steps[$sIdx];
                $currentTime += rand(15, 120); // transition time between 15s to 2 mins

                $meta = [
                    'screen'   => $step['screen'],
                    'platform' => $platform,
                ];

                if ($sIdx >= 4) {
                    $meta['service_name'] = $service['name'];
                }
                if ($sIdx >= 8) {
                    $meta['payment_method'] = $paymentMethod;
                    $meta['amount'] = rand(499, 3999);
                }

                $batchEvents[] = $makeEvent([
                    'session_id'       => $sessionId,
                    'user_id'          => $userId,
                    'platform'         => $platform,
                    'device'           => $device,
                    'app_version'      => $appVersion,
                    'event_name'       => $step['event_name'],
                    'screen_name'      => $step['screen'],
                    'service_id'       => $service['id'],
                    'service_category' => $service['name'],
                    'provider_id'      => rand(1, 10),
                    'city'             => $city,
                    'payment_method'   => $paymentMethod,
                    'campaign_source'  => rand(1, 10) > 6 ? 'google_ads' : 'organic',
                    'is_new_user'      => $isNewUser,
                    'metadata'         => $meta,
                    'ip_address'       => '103.' . rand(10, 200) . '.' . rand(1, 255) . '.' . rand(1, 255),
                    'created_at'       => date('Y-m-d H:i:s', $currentTime),
                ]);

                // Inject problem signal events where drop-offs frequently occur!
                if ($sIdx === 7 && $maxStepReached === 7) { // Dropped at Booking Details
                    $currentTime += rand(5, 20);
                    $batchEvents[] = $makeEvent([
                        'session_id'       => $sessionId,
                        'user_id'          => $userId,
                        'platform'         => $platform,
                        'device'           => $device,
                        'app_version'      => $appVersion,
                        'event_name'       => 'validation_error',
                        'screen_name'      => 'BookingFormScreen',
                        'service_id'       => $service['id'],
                        'service_category' => $service['name'],
                        'city'             => $city,
                        'is_new_user'      => $isNewUser,
                        'metadata'         => ['error' => 'Address coordinates outside service boundary', 'field' => 'address_id'],
                        'ip_address'       => '103.' . rand(10, 200) . '.' . rand(1, 255) . '.' . rand(1, 255),
                        'created_at'       => date('Y-m-d H:i:s', $currentTime),
                    ]);
                } elseif ($sIdx === 9 && $maxStepReached === 9) { // Dropped at Payment Initiated
                    $currentTime += rand(10, 30);
                    $batchEvents[] = $makeEvent([
                        'session_id'       => $sessionId,
                        'user_id'          => $userId,
                        'platform'         => $platform,
                        'device'           => $device,
                        'app_version'      => $appVersion,
                        'event_name'       => 'payment_failed',
                        'screen_name'      => 'PaymentGatewayScreen',
                        'service_id'       => $service['id'],
                        'service_category' => $service['name'],
                        'city'             => $city,
                        'payment_method'   => $paymentMethod,
                        'is_new_user'      => $isNewUser,
                        'metadata'         => ['error' => 'Bank payment gateway timeout / user cancelled', 'gateway' => 'Cashfree'],
                        'ip_address'       => '103.' . rand(10, 200) . '.' . rand(1, 255) . '.' . rand(1, 255),
                        'created_at'       => date('Y-m-d H:i:s', $currentTime),
                    ]);
                } elseif ($sIdx === 1 && $maxStepReached === 1 && rand(1, 10) > 5) { // Dropped at OTP
                    $currentTime += rand(10, 30);
                    $batchEvents[] = $makeEvent([
                        'session_id'       => $sessionId,
                        'user_id'          => $userId,
                        'platform'         => $platform,
                        'device'           => $device,
                        'app_version'      => $appVersion,
                        'event_name'       => 'validation_error',
                        'screen_name'      => 'OtpVerificationScreen',
                        'city'             => $city,
                        'is_new_user'      => 1,
                        'metadata'         => ['error' => 'Invalid OTP code entered (3 attempts exceeded)'],
                        'ip_address'       => '103.' . rand(10, 200) . '.' . rand(1, 255) . '.' . rand(1, 255),
                        'created_at'       => date('Y-m-d H:i:s', $currentTime),
                    ]);
                }
            }
        }

        // Insert batch in chunks of 100
        $chunks = array_chunk($batchEvents, 100);
        $totalInserted = 0;
        foreach ($chunks as $chunk) {
            $inserted = $db->table('analytics_events')->insertBatch($chunk);
            $totalInserted += $inserted;
        }

        return [
            'status'         => 'success',
            'total_events'   => $totalInserted,
            'total_journeys' => $totalJourneys,
            'days_covered'   => $days,
        ];
    }

    protected function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . 's';
        }
        $mins = floor($seconds / 60);
        $remSecs = $seconds % 60;
        return $mins . 'm ' . ($remSecs > 0 ? $remSecs . 's' : '');
    }

    protected function getSignalSeverity(string $eventName): string
    {
        switch ($eventName) {
            case 'payment_failed':
            case 'api_error':
                return 'danger';
            case 'validation_error':
            case 'location_permission_denied':
            case 'session_timeout':
                return 'warning';
            default:
                return 'info';
        }
    }
}
