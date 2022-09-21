<?php

namespace App\Console;

use App\Console\Commands\UpdateWaterStatusCommand;
use App\Console\Commands\GetTsaLeadIdCommand;
use Ignite\Commands\IgniteFetchCommand;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\GetSellStatusCommand;
use App\Modules\PropertyMe\Commands\SyncAgent;
use App\Console\Commands\SaveTsaCallHistoryCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UploadConnectionDataToSFTPCommand;
use App\Modules\PropertyMe\Commands\SavePropertyMeLeadsCommand;
use App\Modules\PropertyMe\Commands\SetPropertyMeAgentEmailCommand;
use Origin\Commands\OriginStorePlanCommand;
use Origin\Commands\OriginCheckStatusCommand;


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
        SaveTsaCallHistoryCommand::class,
        SetPropertyMeAgentEmailCommand::class,
        OriginStorePlanCommand::class,
        OriginCheckStatusCommand::class,
        UpdateWaterStatusCommand::class,
        GetTsaLeadIdCommand::class,
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
         $schedule->command('property_me:save_contact')->everyThirtyMinutes();
         $schedule->command('origin:check')->hourlyAt(45);

         if($this->shouldIgniteRun()){
            $schedule->command('ignite:fetch')->everyTenMinutes();
         }

         $this->registerWaterStatusUpdate($schedule);

         $this->registerSaveTsaCallHistory($schedule);

        $schedule->command('fetch:get-tsa-lead-id')->everyTenMinutes();
    }

    private function registerWaterStatusUpdate(Schedule $schedule)
    {
        $schedule->command('water:update-status')->timezone(11)->dailyAt("0:00");
        $schedule->command('water:update-status')->timezone(11)->dailyAt("5:00");
    }

    private function registerSaveTsaCallHistory(Schedule $schedule)
    {
        $schedule->command('tsa:save-call-history')->everyThirtyMinutes();
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
