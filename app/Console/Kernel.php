<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Ignite\Commands\IgniteFetchCommand;
use App\Console\Commands\GetSellStatusCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UploadConnectionDataToSFTPCommand;
use Ignite\Jobs\IgniteFetchJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetSellStatusCommand::class,
        UploadConnectionDataToSFTPCommand::class,
        IgniteFetchCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('fetch:submitted-leads')->daily();
         $schedule->command('fetch:submitted-water-leads');
         $schedule->command('ea:upload:lead')->daily();

         if($this->shouldIgniteRun()){
            $schedule->command('ignite:fetch')->everyTenMinutes();
         }
    }

    private function shouldIgniteRun(){
        $igniteStart = config('ignite.IGNITE_IS_ACTIVE') ?? false;
        return $igniteStart;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
