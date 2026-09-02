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
}
