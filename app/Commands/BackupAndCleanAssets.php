<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BackupAndCleanAssets extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Maintenance';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'assets:clean';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Safely backs up unwanted/legacy assets to writable/backups/ and removes them from public/.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'assets:clean [options]';

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--dry-run' => 'Simulate backup and removal without modifying any files.',
        '--force'   => 'Bypass manual confirmation prompt.',
    ];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null;
        $force  = CLI::getOption('force') !== null;

        CLI::write('====================================================', 'cyan');
        CLI::write('    SAFE ASSETS BACKUP & CLEANUP UTILITY            ', 'yellow');
        CLI::write('====================================================', 'cyan');
        CLI::write('Timestamp: ' . date('Y-m-d H:i:s T'), 'light_gray');
        CLI::newLine();

        if ($dryRun) {
            CLI::write('>>> DRY-RUN MODE: No files will be moved or deleted <<<', 'light_red');
            CLI::newLine();
        }

        $rootPath = ROOTPATH;
        $backupBaseDir = WRITEPATH . 'backups/';
        $timestamp = date('Ymd_His');
        $backupDir = $backupBaseDir . 'assets_backup_' . $timestamp . '/';
        $zipFile   = $backupBaseDir . 'assets_backup_' . $timestamp . '.zip';

        // List of unwanted asset targets to backup and remove
        $targets = [
            'assets1' => [
                'type' => 'dir',
                'path' => $rootPath . 'public/assets1',
                'desc' => 'Legacy unused admin template assets (css, js, fonts, images, plugins)',
            ],
            'responsive1_css' => [
                'type' => 'file',
                'path' => $rootPath . 'public/assets/css/responsive1.css',
                'desc' => 'Junk CSS file with 12 bytes of random test characters',
            ],
            'fields_txt' => [
                'type' => 'file',
                'path' => $rootPath . 'public/fields.txt',
                'desc' => 'Legacy debug JSON dump file in public root',
            ],
            'root_uploads' => [
                'type' => 'dir',
                'path' => $rootPath . 'uploads',
                'desc' => 'Duplicate empty/unused root uploads directory (real uploads are in public/uploads)',
            ],
        ];

        // Step 1: Scan and verify existing targets
        CLI::write('[1/4] Scanning targets for unwanted assets...', 'cyan');
        $foundTargets = [];
        $totalFiles = 0;
        $totalBytes = 0;

        foreach ($targets as $key => $target) {
            $path = $target['path'];
            if (file_exists($path)) {
                $stat = $this->calculateDirStats($path);
                $foundTargets[$key] = array_merge($target, $stat);
                $totalFiles += $stat['count'];
                $totalBytes += $stat['size'];

                $sizeFormatted = $this->formatBytes($stat['size']);
                CLI::write("  -> Found [{$target['type']}]: {$path} ({$stat['count']} files, {$sizeFormatted})", 'yellow');
                CLI::write("     Reason: {$target['desc']}", 'light_gray');
            } else {
                CLI::write("  -> Skipped (not found): {$path}", 'dark_gray');
            }
        }

        if (empty($foundTargets)) {
            CLI::write('✅ No unwanted assets found. Everything is already clean!', 'green');
            return;
        }

        $totalFormatted = $this->formatBytes($totalBytes);
        CLI::write("Total to backup and remove: {$totalFiles} files ({$totalFormatted})", 'light_cyan');
        CLI::newLine();

        if ($dryRun) {
            CLI::write('Dry run complete. Target files verified.', 'green');
            return;
        }

        // Step 2: Create Backup
        CLI::write("[2/4] Taking full backup to {$backupDir}...", 'cyan');
        if (!is_dir($backupBaseDir)) {
            mkdir($backupBaseDir, 0755, true);
        }
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $manifest = [
            'created_at'  => date('c'),
            'total_files' => $totalFiles,
            'total_bytes' => $totalBytes,
            'targets'     => [],
        ];

        $backedUpFiles = 0;
        $backedUpBytes = 0;

        foreach ($foundTargets as $key => $target) {
            $destSub = $backupDir . $key . '/';
            if ($target['type'] === 'dir') {
                $copied = $this->copyDirectory($target['path'], $destSub);
            } else {
                if (!is_dir($destSub)) {
                    mkdir($destSub, 0755, true);
                }
                $destFile = $destSub . basename($target['path']);
                copy($target['path'], $destFile);
                $copied = [
                    'count' => 1,
                    'size'  => filesize($destFile),
                    'files' => [$destFile],
                ];
            }

            $manifest['targets'][$key] = [
                'original_path' => $target['path'],
                'backup_path'   => $destSub,
                'type'          => $target['type'],
                'file_count'    => $copied['count'],
                'byte_size'     => $copied['size'],
            ];

            $backedUpFiles += $copied['count'];
            $backedUpBytes += $copied['size'];
        }

        // Write manifest
        file_put_contents($backupDir . 'manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));

        // Optionally create ZIP archive
        $zipCreated = false;
        if (class_exists('\ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                $this->addFolderToZip($backupDir, $zip, strlen($backupDir));
                $zip->close();
                if (file_exists($zipFile) && filesize($zipFile) > 0) {
                    $zipCreated = true;
                    CLI::write("  📦 ZIP Archive created: {$zipFile} (" . $this->formatBytes(filesize($zipFile)) . ")", 'green');
                }
            }
        }

        CLI::write("  ✅ Backup folder completed: {$backupDir} ({$backedUpFiles} files, " . $this->formatBytes($backedUpBytes) . ')', 'green');
        CLI::newLine();

        // Step 3: Verification Phase
        CLI::write('[3/4] Verifying backup integrity before deletion...', 'cyan');
        if ($backedUpFiles < $totalFiles || $backedUpBytes < $totalBytes) {
            CLI::error("Backup verification failed! Expected {$totalFiles} files ({$totalBytes} bytes), got {$backedUpFiles} files ({$backedUpBytes} bytes). Aborting removal!");
            return;
        }
        CLI::write('  ✅ Integrity check passed: 100% of files and bytes backed up successfully.', 'green');
        CLI::newLine();

        // Step 4: Removal Phase
        CLI::write('[4/4] Safely removing unwanted assets...', 'cyan');
        $removedTargets = 0;
        foreach ($foundTargets as $key => $target) {
            $path = $target['path'];
            if ($target['type'] === 'dir') {
                $deleted = $this->deleteDirectory($path);
            } else {
                $deleted = @unlink($path);
            }

            if ($deleted && !file_exists($path)) {
                CLI::write("  🗑️  Successfully removed: {$path}", 'green');
                $removedTargets++;
            } else {
                CLI::write("  ⚠️ Failed to delete or partially deleted: {$path}", 'yellow');
            }
        }

        CLI::newLine();
        CLI::write('====================================================', 'green');
        CLI::write('         CLEANUP COMPLETED SUCCESSFULLY             ', 'light_green');
        CLI::write('====================================================', 'green');
        CLI::write("Total removed targets: {$removedTargets}", 'green');
        CLI::write("Disk space reclaimed:  {$totalFormatted}", 'green');
        CLI::write("Backup stored at:     {$backupDir}", 'light_cyan');
        if ($zipCreated) {
            CLI::write("ZIP backup at:        {$zipFile}", 'light_cyan');
        }
    }

    /**
     * Recursively calculate file count and byte size of a directory or single file.
     */
    private function calculateDirStats(string $path): array
    {
        if (is_file($path)) {
            return [
                'count' => 1,
                'size'  => filesize($path) ?: 0,
            ];
        }

        $count = 0;
        $size  = 0;

        if (!is_dir($path)) {
            return ['count' => 0, 'size' => 0];
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $count++;
                $size += $item->getSize();
            }
        }

        return ['count' => $count, 'size' => $size];
    }

    /**
     * Recursively copy a directory to a new location.
     */
    private function copyDirectory(string $source, string $dest): array
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $count = 0;
        $size  = 0;
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $sourceLen = strlen(rtrim($source, '/\\'));
        foreach ($iterator as $item) {
            $subPath = ltrim(substr($item->getPathname(), $sourceLen), '/\\');
            $targetPath = $dest . DIRECTORY_SEPARATOR . $subPath;

            if ($item->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } elseif ($item->isFile()) {
                $dir = dirname($targetPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                copy($item->getPathname(), $targetPath);
                $count++;
                $size += filesize($targetPath);
                $files[] = $targetPath;
            }
        }

        return ['count' => $count, 'size' => $size, 'files' => $files];
    }

    /**
     * Recursively add folder to ZipArchive.
     */
    private function addFolderToZip(string $folder, \ZipArchive $zip, int $exclusiveLength): void
    {
        $handle = opendir($folder);
        if (!$handle) return;

        while (false !== ($f = readdir($handle))) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zip->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    $zip->addEmptyDir($localPath);
                    $this->addFolderToZip($filePath, $zip, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * Recursively delete directory.
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return @unlink($dir);
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }

        return @rmdir($dir);
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
