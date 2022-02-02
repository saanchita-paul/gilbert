<?php

namespace App\Console;

use App\Modules\PropertyMe\Commands\SavePropertyMeLeadsCommand;
use App\Modules\PropertyMe\Commands\SyncAgent;
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
        SavePropertyMeLeadsCommand::class,
        SyncAgent::class,
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
         $schedule->command('fetch:submitted-leads')->hourly();
         $schedule->command('ea:upload:lead')->daily();
         $schedule->command('property_me:save_contact')->everyFifteenMinutes();

         if($this->shouldIgniteRun()){
            $schedule->command('ignite:fetch')->everyTenMinutes();
         }

         $this->registerWaterStatusUpdate($schedule);
    }

    private function registerWaterStatusUpdate(Schedule $schedule)
    {
        $schedule->command('fetch:submitted-water-leads')->timezone(11)->dailyAt("0:00");
        $schedule->command('fetch:submitted-water-leads')->timezone(11)->dailyAt("5:00");
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
