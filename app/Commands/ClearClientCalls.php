<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ClearClientCalls extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Call Analysis';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'calls:clear-client';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Clears all Client & Provider call recordings, jobs, analysis, and keyword data from the database.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'calls:clear-client [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--dry-run' => 'Simulate the deletion without removing any records.',
    ];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null;

        CLI::write('====================================================', 'cyan');
        CLI::write('     CLEAR CLIENT & PROVIDER CALL RECORDINGS        ', 'yellow');
        CLI::write('====================================================', 'cyan');

        if ($dryRun) {
            CLI::write('>>> RUNNING IN DRY-RUN MODE (No data will be deleted) <<<', 'light_red');
        }

        $db = \Config\Database::connect();

        $clientCallWhere = "(call_type IN ('client_provider', 'client_sp') OR (customer_id IS NOT NULL AND customer_id > 0 AND (verifier_id IS NULL OR verifier_id = 0)))";

        // 1. Fetch target call recordings
        $recordings = $db->table('call_recordings')
            ->select('id, recording_url, call_type, service_type, customer_id, provider_id, created_at')
            ->where($clientCallWhere)
            ->get()
            ->getResultArray();

        $totalRecordings = count($recordings);
        CLI::write("Found {$totalRecordings} Client & Provider call recording(s).", $totalRecordings > 0 ? 'yellow' : 'green');

        if ($totalRecordings === 0) {
            CLI::write('No Client & Provider calls found to delete.', 'green');
            return;
        }

        $recordingIds = array_column($recordings, 'id');

        // 2. Fetch related call_analysis IDs
        $analyses = $db->table('call_analysis')
            ->select('id, recording_id')
            ->whereIn('recording_id', $recordingIds)
            ->get()
            ->getResultArray();

        $analysisIds = !empty($analyses) ? array_column($analyses, 'id') : [];
        $totalAnalyses = count($analysisIds);
        CLI::write("Found {$totalAnalyses} associated call_analysis record(s).", 'light_gray');

        // 3. Fetch keywords count
        $totalKeywords = 0;
        if (!empty($analysisIds)) {
            $totalKeywords = $db->table('call_keywords')
                ->whereIn('analysis_id', $analysisIds)
                ->countAllResults();
        }
        CLI::write("Found {$totalKeywords} associated call_keywords record(s).", 'light_gray');

        // 4. Fetch jobs count
        $totalJobs = $db->table('call_analysis_jobs')
            ->whereIn('recording_id', $recordingIds)
            ->countAllResults();
        CLI::write("Found {$totalJobs} associated call_analysis_jobs record(s).", 'light_gray');

        if ($dryRun) {
            CLI::write('Dry run complete. Exiting without changes.', 'yellow');
            return;
        }

        // Perform deletion within transaction
        $db->transStart();

        // Delete keywords
        if (!empty($analysisIds)) {
            $db->table('call_keywords')->whereIn('analysis_id', $analysisIds)->delete();
        }

        // Delete analysis
        $db->table('call_analysis')->whereIn('recording_id', $recordingIds)->delete();

        // Delete jobs
        $db->table('call_analysis_jobs')->whereIn('recording_id', $recordingIds)->delete();

        // Delete recordings
        $db->table('call_recordings')->whereIn('id', $recordingIds)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            CLI::error('Transaction failed! Database rollback occurred.');
            return;
        }

        // Clean up audio files if stored locally
        $filesDeleted = 0;
        foreach ($recordings as $rec) {
            if (!empty($rec['recording_url'])) {
                $url = $rec['recording_url'];
                $cleanUrl = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                $localPaths = [
                    ROOTPATH . $cleanUrl,
                    ROOTPATH . 'public/' . $cleanUrl,
                    ROOTPATH . 'public/uploads/call_recordings/' . basename($url)
                ];

                foreach ($localPaths as $path) {
                    if (file_exists($path) && is_file($path)) {
                        @unlink($path);
                        $filesDeleted++;
                        break;
                    }
                }
            }
        }

        CLI::newLine();
        CLI::write('✅ Client & Provider Calls successfully cleared:', 'green');
        CLI::write("  - Call Recordings: {$totalRecordings}", 'green');
        CLI::write("  - Call Analyses:   {$totalAnalyses}", 'green');
        CLI::write("  - Keywords:        {$totalKeywords}", 'green');
        CLI::write("  - Analysis Jobs:   {$totalJobs}", 'green');
        CLI::write("  - Audio Files:     {$filesDeleted}", 'green');
        CLI::write('Database cleared cleanly.', 'green');
    }
}
