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
        // adding limitation for API usage
        $schedule
            ->command('app:update-weather')
            ->hourly()
            ->between('6:00', '20:00');

        // adding limitation for API usage
        $schedule
            ->command('app:average-weather-calculation')
            ->everyMinute()
            ->between('6:00', '20:00');

        $schedule
            ->command('app:send-notifications')
            ->weekdays()
            ->everyTwoHours()
            ->timezone('America/Chicago')
            ->between('8:00', '17:00');
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
