<?php

namespace easyCRM\Console;

use easyCRM\Estado;
use easyCRM\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            User::query()->update(['assigned_leads' => 0]);
        })->dailyAt('01:20');

        $schedule->call(function () {
            set_time_limit(0);

            $disk_public = Storage::disk('public');

            $current_timestamp = time() - (3600 * 3600);
            $allFiles = $disk_public->allFiles('files' . DIRECTORY_SEPARATOR . 'excel-exports');

            foreach ($allFiles as $file) {
                // delete the files older then 15 days..
                if ($disk_public->lastModified($file) <= $current_timestamp) {
                    $disk_public->delete($file);
                }
            }
        })->dailyAt('04:20');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
