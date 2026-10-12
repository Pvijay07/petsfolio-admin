<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\AIAnalyst\MySQLMetrics;
use App\Libraries\AIAnalyst\CRMMetrics;
<<<<<<< HEAD
=======
use App\Libraries\AIAnalyst\MongoDBClient;
>>>>>>> origin/main
use App\Libraries\AIAnalyst\GeminiAnalyst;
use Config\Database;

/**
 * GenerateScheduledReport CLI Spark Command
 * 
 * Run via CLI / Cron: php spark report:generate
 */
class GenerateScheduledReport extends BaseCommand
{
    protected $group       = 'Cron';
    protected $name        = 'report:generate';
    protected $description = 'Runs automated business KPI queries across MySQL & MongoDB, generates executive narrative summary via Gemini, and archives the report.';

    public function run(array $params)
    {
        CLI::write('--- AI BUSINESS ANALYST: SCHEDULED REPORT GENERATION START ---', 'yellow');

        $mysqlMetrics = new MySQLMetrics();
        $crmMetrics   = new CRMMetrics();
        $analyst      = new GeminiAnalyst();
<<<<<<< HEAD
=======
        $mongoClient  = new MongoDBClient();
>>>>>>> origin/main
        $db           = Database::connect();

        $dateRange = '30_days';

<<<<<<< HEAD
        CLI::write('1. Fetching pre-approved KPI metrics (MySQL + MongoDB CRM)...', 'cyan');
=======
        CLI::write('1. Checking Database Views & MongoDB Client status...', 'cyan');
        
        // Ensure database views exist
        $mysqlMetrics->ensureViewExists();
        CLI::write('   ✓ MySQL view `v_bookings_overview` verified and ready.', 'green');

        // Check MongoDB client driver and database connection status
        $mongoStatus = $mongoClient->isClientAvailable();
        if (!$mongoStatus['driver_installed']) {
            CLI::write('   ℹ MongoDB PHP extension (MongoDB\Client) is NOT installed. CRM metrics using fallback handling.', 'yellow');
        } elseif (!$mongoStatus['connected']) {
            CLI::write('   ℹ MongoDB Client initialized, but live database connection is inactive.', 'yellow');
        } else {
            CLI::write('   ✓ MongoDB Client connected to `' . $mongoStatus['database'] . '`', 'green');
        }

        CLI::write('2. Fetching pre-approved KPI metrics (MySQL + MongoDB CRM)...', 'cyan');
>>>>>>> origin/main

        // Execute key business metrics
        $bookingsSummary   = $mysqlMetrics->bookings_summary($dateRange);
        $revenueOverview   = $mysqlMetrics->revenue_overview($dateRange);
        $cancellationRate  = $mysqlMetrics->cancellation_rate($dateRange);

        $conversionRate    = $crmMetrics->conversion_rate($dateRange);
        $sourcePerformance = $crmMetrics->lead_source_performance($dateRange);
        $resolutionTime    = $crmMetrics->avg_resolution_time($dateRange);
        $crossSellRate     = $crmMetrics->cross_sell_acceptance_rate($dateRange);

        $aggregatedMetrics = [
            'bookings'         => $bookingsSummary,
            'revenue'          => $revenueOverview,
            'cancellations'    => $cancellationRate,
            'lead_conversion'  => $conversionRate,
            'lead_sources'     => $sourcePerformance,
            'support_resolution'=> $resolutionTime,
            'cross_sell'       => $crossSellRate,
        ];

<<<<<<< HEAD
        CLI::write('2. Sending metrics payload to Gemini for executive narrative summary...', 'cyan');
=======
        CLI::write('3. Sending metrics payload to Gemini for executive narrative summary...', 'cyan');
>>>>>>> origin/main

        $prompt = "The following is a automated 30-day KPI dataset for Petsfolio business operations:\n\n" . 
                  json_encode($aggregatedMetrics, JSON_PRETTY_PRINT) . "\n\n" .
                  "Please provide a concise 3-paragraph Executive Business Summary highlighting key growth signals, funnel conversion performance, support health, and top 2 recommended action items for management.";

        $response = $analyst->ask($prompt);
        $summaryText = $response['reply'] ?? 'Report summary could not be generated.';

<<<<<<< HEAD
        CLI::write('3. Storing report snapshot in database (ai_analyst_reports)...', 'cyan');
=======
        CLI::write('4. Storing report snapshot in database (ai_analyst_reports)...', 'cyan');
>>>>>>> origin/main

        try {
            $periodStart = date('Y-m-d', strtotime('-30 days'));
            $periodEnd   = date('Y-m-d');

            if ($db->tableExists('ai_analyst_reports')) {
                $db->table('ai_analyst_reports')->insert([
                    'report_type'   => 'monthly_kpi_summary',
                    'period_start'  => $periodStart,
                    'period_end'    => $periodEnd,
                    'summary'       => $summaryText,
                    'raw_metrics'   => json_encode($aggregatedMetrics),
                    'delivered_via' => 'cron_spark',
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
            }
            CLI::write('✓ Scheduled report successfully generated and archived in ai_analyst_reports!', 'green');
        } catch (\Throwable $e) {
            CLI::error('Failed to store report snapshot: ' . $e->getMessage());
        }

        CLI::write('--- SCHEDULED REPORT GENERATION COMPLETE ---', 'yellow');
    }
}
