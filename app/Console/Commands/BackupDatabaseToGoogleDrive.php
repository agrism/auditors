<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use App\Services\GoogleDriveService;
use Illuminate\Console\Command;

class BackupDatabaseToGoogleDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup-google-drive 
                            {--local-only : Only create local backup without uploading to Google Drive}
                            {--force : Force upload even if not in production environment}
                            {--keep= : Override default retention days for remote cleanup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a compressed MySQL database backup and upload it to Google Drive (Production)';

    public function handle(DatabaseBackupService $backupService, GoogleDriveService $driveService): int
    {
        $startTime = microtime(true);
        $this->info('Starting database backup process...');

        // Verify production environment unless --force or --local-only is passed
        $isProduction = app()->environment('production', 'prod');
        if (!$isProduction && !$this->option('force') && !$this->option('local-only')) {
            $this->warn('Notice: Google Drive backup is configured to run only in production environment (current: ' . app()->environment() . ').');
            $this->line('Use <comment>--force</comment> to force upload, or <comment>--local-only</comment> to dump locally.');
            return Command::SUCCESS;
        }

        try {
            // 1. Create SQL database dump
            $this->info('Dumping database...');
            $filePath = $backupService->dumpDatabase();
            $fileName = basename($filePath);
            $fileSize = round(filesize($filePath) / 1024 / 1024, 2);

            $this->info("Database dumped: <comment>{$fileName}</comment> ({$fileSize} MB)");

            // 2. Upload to Google Drive
            if (!$this->option('local-only')) {
                $this->info('Uploading backup to Google Drive...');
                $driveResult = $driveService->uploadFile($filePath, $fileName);
                $fileId = $driveResult['id'] ?? 'unknown';

                $this->info("<info>✓</info> Successfully uploaded to Google Drive! (File ID: <comment>{$fileId}</comment>)");

                // 3. Clean up expired remote backups
                $retentionDays = $this->option('keep') ? (int) $this->option('keep') : config('services.google_drive.retention_days', 30);
                if ($retentionDays > 0) {
                    $this->info("Cleaning up Google Drive backups older than {$retentionDays} days...");
                    $deleted = $driveService->cleanupOldBackups(null, $retentionDays);
                    if ($deleted > 0) {
                        $this->info("Cleaned up <comment>{$deleted}</comment> expired backup(s) from Google Drive.");
                    }
                }
            } else {
                $this->warn('Skipped Google Drive upload (--local-only specified).');
            }

            $duration = round(microtime(true) - $startTime, 2);
            $this->info("<info>✓ Database backup finished successfully in {$duration}s.</info>");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
