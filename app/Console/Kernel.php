<?php

namespace App\Console;

use App\Console\KernelInvokable\ImportFeedback;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Throwable;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // import whenever the schedule:run is called otherwise every hour in dev/prod environment
        if (App::environment("local")) {
            $schedule->call(new ImportFeedback);
        } else {
            $schedule->call(new ImportFeedback)
                ->hourly()
                ->onFailure(function (Exception $e) {
                    Log::error($e->getMessage());
                    mail('admin@example.fr', 'Import Feedback failed', '');
                });
        }
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
