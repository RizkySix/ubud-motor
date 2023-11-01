<?php

namespace App\Console;

use App\Jobs\ChargeMotor;
use App\Jobs\ExtensionReminder;
use App\Jobs\PaymentReminder;
use App\Jobs\UnActiveBooking;
use App\Jobs\UnConfirmedBooking;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new UnActiveBooking())->dailyAt('23:00');
        $schedule->job(new UnConfirmedBooking())->dailyAt('23:00');
        $schedule->job(new PaymentReminder())->hourlyAt(0);
        $schedule->job(new ExtensionReminder())->dailyAt('15:00');
        $schedule->job(new ChargeMotor())->hourlyAt(0);
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
