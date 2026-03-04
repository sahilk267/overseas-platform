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
        // 8a: Dispatch notifications every 15 minutes
        $schedule->command('umaep:send-notifications')->everyFifteenMinutes()->withoutOverlapping();

        // 8b: Cleanup expired sessions daily
        $schedule->command('umaep:cleanup-sessions')->daily()->withoutOverlapping();

        // 8c: Archive audit logs weekly
        $schedule->command('umaep:archive-audit-logs')->weekly()->withoutOverlapping();

        // 8d: Generate pending reports hourly
        $schedule->command('umaep:generate-reports')->hourly()->withoutOverlapping();

        // 8e: Check contract expirations daily
        $schedule->command('umaep:check-contracts')->daily()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
