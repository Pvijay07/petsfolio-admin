<?php

namespace Tests;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/TestCase.php';
require_once __DIR__ . '/booking/BookingTests.php';
require_once __DIR__ . '/cancellation/CancellationTests.php';
require_once __DIR__ . '/provider/ProviderTests.php';
require_once __DIR__ . '/customer/CustomerTests.php';
require_once __DIR__ . '/revenue/RevenueTests.php';
require_once __DIR__ . '/payment/PaymentTests.php';
require_once __DIR__ . '/wallet/WalletTests.php';
require_once __DIR__ . '/operations/OperationsTests.php';
require_once __DIR__ . '/funnel/FunnelTests.php';
require_once __DIR__ . '/regression/RegressionTests.php';
require_once __DIR__ . '/complex/ComplexTests.php';

use App\Libraries\AIAnalyst\IntentEngine\IntentEngine;
use App\Libraries\AIAnalyst\MySQLMetrics;
use App\Libraries\AIAnalyst\QueryValidator;

class TestRunner
{
    private array $tests = [];
    private array $results = [];
    private MySQLMetrics $metrics;
    private QueryValidator $validator;

    public function __construct()
    {
        $this->metrics = new MySQLMetrics();
        $this->validator = new QueryValidator();
        $this->loadTests();
    }

    private function loadTests(): void
    {
        $this->tests = array_merge(
            Booking\BookingTests::getTests(),
            Cancellation\CancellationTests::getTests(),
            Provider\ProviderTests::getTests(),
            Customer\CustomerTests::getTests(),
            Revenue\RevenueTests::getTests(),
            Payment\PaymentTests::getTests(),
            Wallet\WalletTests::getTests(),
            Operations\OperationsTests::getTests(),
            Funnel\FunnelTests::getTests(),
            Regression\RegressionTests::getTests(),
            Onboarding\OnboardingTests::getTests(),
            Pets\PetTests::getTests(),
            Feedback\FeedbackTests::getTests(),
            Refund\RefundTests::getTests(),
            Tracking\TrackingTests::getTests(),
            Bidding\BiddingTests::getTests(),
            Complex\ComplexTests::getTests()
        );
    }

    public function run(): array
    {
        global $gt_mysqli;

        echo "\n" . str_repeat('=', 80) . "\n";
        echo "  PETSFOLIO BI ANALYST AUTOMATED QA & ACCURACY REGRESSION TEST HARNESS\n";
        echo "  Total Test Questions to execute: " . count($this->tests) . "\n";
        echo str_repeat('=', 80) . "\n\n";

        $passedCount = 0;
        $failedCount = 0;

        $intentCorrect = 0;
        $entityCorrect = 0;
        $metricCorrect = 0;
        $filterCorrect = 0;
        $dateCorrect = 0;
        $sqlCorrect = 0;
        $resultCorrect = 0;
        $answerCorrect = 0;
        $followupCorrect = 0;
        $followupTotal = 0;

        $failureTypes = [];

        foreach ($this->tests as $idx => $tc) {
            $num = $idx + 1;
            $isFollowup = ($tc->style === 'followup');
            if ($isFollowup) {
                $followupTotal++;
            }

            echo sprintf("[%02d/%02d] [%-12s] [%-10s] %s ... ", $num, count($this->tests), strtoupper($tc->domain), strtoupper($tc->style), substr($tc->question, 0, 40));

            // Stage 1 to 5: Run IntentEngine Pipeline
            $trace = IntentEngine::debug($tc->question, $tc->historyText, $tc->conversationState ?? null);
            $plan = $trace['plan'];

            // 1. Intent Validation
            $detectedIntent = $trace['resolution']['intent_id'] ?? null;
            $intentOk = false;
            if ($tc->expectedIntentId === null) {
                $intentOk = true;
            } elseif (is_array($tc->expectedIntentId)) {
                $intentOk = in_array($detectedIntent, $tc->expectedIntentId);
            } else {
                $intentOk = ($detectedIntent === $tc->expectedIntentId);
            }

            // 2. Entity Validation
            $entities = $trace['entities'] ?? [];
            $entityOk = true;
            foreach ($tc->expectedEntities as $k => $expectedVal) {
                $actualVal = $entities[$k] ?? null;
                if ($actualVal !== $expectedVal) {
                    $entityOk = false;
                    break;
                }
            }

            // 3. Metric Plan Validation
            $allMetricNames = !empty($plan['metrics']) ? array_column($plan['metrics'], 'name') : [];
            $plannedMetric = !empty($allMetricNames) ? implode(', ', $allMetricNames) : null;
            if ($tc->expectedMetric === null || ($plan['type'] ?? '') === 'followup_solution') {
                $metricOk = true;
            } elseif (is_array($tc->expectedMetric)) {
                $metricOk = count(array_intersect($tc->expectedMetric, $allMetricNames)) > 0;
            } else {
                $metricOk = in_array($tc->expectedMetric, $allMetricNames);
            }

            // 4. Database Execution & Ground Truth Comparison
            $dbOk = true;
            $groundTruth = null;
            $metricData = [];
            $validationResult = ['pass' => true];

            if ($tc->groundTruthClosure !== null) {
                try {
                    $groundTruth = ($tc->groundTruthClosure)($gt_mysqli);
                } catch (\Throwable $e) {
                    $dbOk = false;
                    $validationResult = ['pass' => false, 'error_type' => 'DATA_QUALITY_ERROR', 'details' => "Ground truth query exception: " . $e->getMessage()];
                }
            }

            if ($dbOk && $plan && !empty($plan['metrics'])) {
                foreach ($plan['metrics'] as $mItem) {
                    try {
                        $mName = $mItem['name'];
                        $mArgs = $mItem['args'] ?? [];
                        $execRes = $this->validator->executeMetric($mName, $mArgs);
                        if ($execRes['success']) {
                            $resData = $execRes['data'] ?? [];
                            if (is_array($resData)) {
                                $metricData = array_merge($metricData, $resData);
                                $metricData[$mName] = $resData;
                            }
                        } else {
                            $dbOk = false;
                            $validationResult = ['pass' => false, 'error_type' => 'SQL_GENERATION_ERROR', 'details' => "Metric execution error ({$mName}): " . ($execRes['error'] ?? 'unknown error')];
                            break;
                        }
                    } catch (\Throwable $e) {
                        $dbOk = false;
                        $validationResult = ['pass' => false, 'error_type' => 'SQL_GENERATION_ERROR', 'details' => "Metric execution failed ({$mName}): " . $e->getMessage()];
                        break;
                    }
                }
            }

            // 5. Result Validation
            if ($dbOk && $tc->validationClosure !== null && $groundTruth !== null) {
                try {
                    $validationResult = ($tc->validationClosure)($metricData ?? [], $groundTruth);
                } catch (\Throwable $e) {
                    $validationResult = ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Validator exception: " . $e->getMessage()];
                }
            }

            // Evaluate Final Status
            $testPass = $intentOk && $entityOk && $metricOk && $dbOk && ($validationResult['pass'] ?? false);

            if ($intentOk) $intentCorrect++;
            if ($entityOk) $entityCorrect++;
            if ($metricOk) $metricCorrect++;
            if ($entityOk && $metricOk) $filterCorrect++;
            if ($entityOk) $dateCorrect++;
            if ($dbOk) $sqlCorrect++;
            if ($validationResult['pass']) $resultCorrect++;
            if ($testPass) $answerCorrect++;
            if ($isFollowup && $testPass) $followupCorrect++;

            if ($testPass) {
                $passedCount++;
                echo "\033[32mPASS\033[0m\n";
            } else {
                $failedCount++;
                $errType = $validationResult['error_type'] ?? (!$intentOk ? 'INTENT_ERROR' : (!$entityOk ? 'ENTITY_ERROR' : (!$metricOk ? 'METRIC_DEFINITION_ERROR' : 'RESULT_INTERPRETATION_ERROR')));
                $details = $validationResult['details'] ?? "Intent: [expected=" . json_encode($tc->expectedIntentId) . ", got={$detectedIntent}], Metric: [expected={$tc->expectedMetric}, got={$plannedMetric}]";
                $failureTypes[$errType] = ($failureTypes[$errType] ?? 0) + 1;

                echo "\033[31mFAIL\033[0m ({$errType}: {$details})\n";
            }

            $this->results[] = [
                'test' => $tc,
                'pass' => $testPass,
                'detected_intent' => $detectedIntent,
                'planned_metric'  => $plannedMetric,
                'validation'      => $validationResult
            ];
        }

        // Summary Report
        $total = count($this->tests);
        $overallAcc = round(($passedCount / $total) * 100, 1);
        $intentAcc  = round(($intentCorrect / $total) * 100, 1);
        $entityAcc  = round(($entityCorrect / $total) * 100, 1);
        $semanticAcc= round(($metricCorrect / $total) * 100, 1);
        $metricAcc  = round(($metricCorrect / $total) * 100, 1);
        $filterAcc  = round(($filterCorrect / $total) * 100, 1);
        $dateAcc    = round(($dateCorrect / $total) * 100, 1);
        $sqlAcc     = round(($sqlCorrect / $total) * 100, 1);
        $resultAcc  = round(($resultCorrect / $total) * 100, 1);
        $answerAcc  = round(($answerCorrect / $total) * 100, 1);
        $followupAcc= $followupTotal > 0 ? round(($followupCorrect / $followupTotal) * 100, 1) : 100.0;

        arsort($failureTypes);

        $report = [
            'total_questions'   => $total,
            'passed'            => $passedCount,
            'failed'            => $failedCount,
            'overall_accuracy'  => $overallAcc,
            'intent_accuracy'   => $intentAcc,
            'entity_accuracy'   => $entityAcc,
            'semantic_accuracy' => $semanticAcc,
            'metric_accuracy'   => $metricAcc,
            'filter_accuracy'   => $filterAcc,
            'date_accuracy'     => $dateAcc,
            'sql_accuracy'      => $sqlAcc,
            'result_accuracy'   => $resultAcc,
            'answer_accuracy'   => $answerAcc,
            'followup_accuracy' => $followupAcc,
            'failure_types'     => $failureTypes
        ];

        $this->printReport($report);
        return $report;
    }

    private function printReport(array $r): void
    {
        echo "\n" . str_repeat('=', 80) . "\n";
        echo "BI ACCURACY REPORT\n";
        echo str_repeat('=', 80) . "\n";
        echo sprintf("%-30s %d\n", "Total questions:", $r['total_questions']);
        echo sprintf("%-30s \033[32m%d\033[0m\n", "Passed:", $r['passed']);
        echo sprintf("%-30s \033[31m%d\033[0m\n", "Failed:", $r['failed']);
        echo "\n";
        echo sprintf("%-30s %s%%\n", "Overall accuracy:", $r['overall_accuracy']);
        echo "\n";
        echo sprintf("%-30s %s%%\n", "Intent accuracy:", $r['intent_accuracy']);
        echo sprintf("%-30s %s%%\n", "Entity accuracy:", $r['entity_accuracy']);
        echo sprintf("%-30s %s%%\n", "Semantic accuracy:", $r['semantic_accuracy']);
        echo sprintf("%-30s %s%%\n", "Metric accuracy:", $r['metric_accuracy']);
        echo sprintf("%-30s %s%%\n", "Filter accuracy:", $r['filter_accuracy']);
        echo sprintf("%-30s %s%%\n", "Date accuracy:", $r['date_accuracy']);
        echo sprintf("%-30s %s%%\n", "SQL accuracy:", $r['sql_accuracy']);
        echo sprintf("%-30s %s%%\n", "Result accuracy:", $r['result_accuracy']);
        echo sprintf("%-30s %s%%\n", "Answer accuracy:", $r['answer_accuracy']);
        echo sprintf("%-30s %s%%\n", "Follow-up accuracy:", $r['followup_accuracy']);
        echo "\n";

        if (!empty($r['failure_types'])) {
            echo "TOP FAILURE TYPES\n";
            echo str_repeat('-', 40) . "\n";
            $i = 1;
            foreach ($r['failure_types'] as $type => $count) {
                echo sprintf("%2d. %-25s %d\n", $i++, $type, $count);
            }
            echo "\n";
        }
        echo str_repeat('=', 80) . "\n\n";
    }
}
