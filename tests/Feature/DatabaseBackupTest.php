<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    public function test_database_backup_local_only_command_succeeds(): void
    {
        $exitCode = Artisan::call('db:backup-google-drive', ['--local-only' => true]);
        $this->assertEquals(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Database dumped:', $output);
        $this->assertStringContainsString('.sql', $output);
        $this->assertStringContainsString('Database backup finished successfully', $output);
    }

    public function test_database_backup_custom_database_parameter(): void
    {
        $exitCode = Artisan::call('db:backup-google-drive', [
            '--local-only' => true,
            '--database' => 'biathlon',
            '--subject' => 'biatlons.kilograms.lv',
        ]);
        $this->assertEquals(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Starting database backup process for biathlon', $output);
        $this->assertStringContainsString('Database dumped: backup_biathlon_', $output);
        $this->assertStringContainsString('Database backup finished successfully', $output);
    }

    public function test_cleanup_old_local_backups(): void
    {
        $backupDir = storage_path('app/backups_test_' . uniqid());
        mkdir($backupDir, 0755, true);

        $oldFile = $backupDir . '/backup_test_old.sql';
        $newFile = $backupDir . '/backup_test_new.sql';

        file_put_contents($oldFile, '-- old backup');
        file_put_contents($newFile, '-- new backup');

        // Set modification time: old file 5 days ago, new file now
        touch($oldFile, time() - (5 * 86400));
        touch($newFile, time());

        $service = app(\App\Services\DatabaseBackupService::class);
        $deleted = $service->cleanupOldLocalBackups(3, $backupDir);

        $this->assertEquals(1, $deleted);
        $this->assertFileDoesNotExist($oldFile);
        $this->assertFileExists($newFile);

        // Clean up test dir
        @unlink($newFile);
        @rmdir($backupDir);
    }

    public function test_database_backup_command_executes_local_cleanup(): void
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $dummyFile = $backupDir . '/test_mock.sql';
        file_put_contents($dummyFile, '-- test mock');

        $mockBackup = $this->mock(\App\Services\DatabaseBackupService::class);
        $mockBackup->shouldReceive('dumpDatabase')->once()->andReturn($dummyFile);
        $mockBackup->shouldReceive('cleanupOldLocalBackups')->with(0)->once()->andReturn(1);

        $exitCode = Artisan::call('db:backup-google-drive', [
            '--local-only' => true,
            '--keep-local' => 0,
        ]);
        $this->assertEquals(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Cleaning up local backups older than 0 days...', $output);
        $this->assertStringContainsString('Cleaned up 1 expired local backup(s)', $output);

        @unlink($dummyFile);
    }
}
