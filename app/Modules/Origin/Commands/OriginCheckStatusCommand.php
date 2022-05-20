<?php

namespace Origin\Commands;

use Illuminate\Console\Command;
use App\Models\ConnectionService;
use Origin\Services\CheckOrderAPI;
use Carbon\Carbon;

class OriginCheckStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'origin:check'
    ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check origin lead statuses and update database if there are any changes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $this->line('Origin check command started!');
        $services = ConnectionService::where('provider_name', ConnectionService::PROVIDER_ORIGIN)
                    ->whereNotNull('lead_reference')
                    ->where('status', ConnectionService::STATUS_SUBMITTED)
                    ->get();
        
        $anyUpdate = false;

        foreach($services as $service){
            try {
                $checkOrder = new CheckOrderAPI($service->lead_reference);
                $response = $checkOrder->fetch();
                if($response['orderStatus'] != CheckOrderAPI::STATUS_IN_PROGRESS){
                    if($response['orderStatus'] == CheckOrderAPI::STATUS_COMPLETE){
                        $service->status = ConnectionService::STATUS_ACCEPTED;
                        $service->accepted_at = Carbon::now();
                    }
                    if($response['orderStatus'] == CheckOrderAPI::STATUS_CANCELLED){
                        $service->status = ConnectionService::STATUS_CLOSED;
                        $service->reason = $response['statusReason'];
                    }
                    $service->save();
                    $anyUpdate = true;
                    $this->line(sprintf('Updated status for service id %s', $service->id));
                }
            } catch (\Exception $e){
                $this->error($e->getMessage());
            }
        }
        
        if(!$anyUpdate)
            $this->line('No updates');
        $this->line('Origin fetch plan lead command finished successfully!');
    }
}
