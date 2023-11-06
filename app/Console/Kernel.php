<?php

namespace App\Console;

use App\Console\KernelInvokable\ImportFeedback;
use App\Console\KernelInvokable\SendFeedbackExportMail;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $admin_role_id = Role::where('alias', 'admin')->first()->id;
        $admin = User::where('role_id', $admin_role_id)->first();

        // import whenever the schedule:run is called otherwise every hour in dev/prod environment
        if (App::environment("local")) {
            $schedule->call(new ImportFeedback);
        } else {
            $schedule->call(new ImportFeedback)
                ->hourly()
                ->onFailure(function (Exception $e) use ($admin) {
                    Log::error($e->getMessage());
                    mail($admin->email, 'Import Feedback failed', '');
                });
        }

        // import whenever the schedule:run is called otherwise every hour in dev/prod environment
        if (App::environment("local")) {
            $schedule->call(new SendFeedbackExportMail);
        } else {
            $schedule->call(new SendFeedbackExportMail)
                ->weekly()
                ->fridays()
                ->at('15:00')
                ->onFailure(function (Exception $e) use ($admin) {
                    Log::error($e->getMessage());
                    mail($admin->email, 'Export Feedback mail sending failed', '');
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
