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
        // Backup command (if enabled)
        if (config('backup.enabled', false)) {
            $schedule->command('backup:create')
                    ->cron(config('backup.schedule', '0 2 * * *'))
                    ->description('Create application backup')
                    ->onFailure(function () {
                        \Log::error('Scheduled backup failed');
                    })
                    ->onSuccess(function () {
                        \Log::info('Scheduled backup completed successfully');
                    });
        }

        // Clear old logs daily
        $schedule->command('log:clear')->daily();

        // Cache optimization (weekly)
        $schedule->command('cache:clear')->weekly();
        $schedule->command('config:clear')->weekly();
        $schedule->command('route:clear')->weekly();
        $schedule->command('view:clear')->weekly();
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
