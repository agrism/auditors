<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auditors.lv database backup
        $schedule->command('db:backup-google-drive --database=auditors --subject=auditors.lv')
            ->dailyAt('02:00')
            ->environments(['production', 'prod'])
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/backup.log'));

        // Biatlons.kilograms.lv database backup
        $schedule->command('db:backup-google-drive --database=biathlon --subject=biatlons.kilograms.lv')
            ->dailyAt('02:30')
            ->environments(['production', 'prod'])
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/backup.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
