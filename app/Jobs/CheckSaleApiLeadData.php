<?php

namespace App\Jobs;

use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotContactService;
use App\Services\Sales\GetSalesRequestStaus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSaleApiLeadData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->checkStatus();
    }

    private function checkStatus()
    {
        $service = new GetSalesRequestStaus();

        $leads = ConnectionApplication::query()->where([['status', '=', ConnectionApplication::STATUS_EA_PROCESSINF]])->get();


        foreach ($leads as $lead) {
            $service->getSalesStatus($lead->ea_sales_id);
            $hubspotService = new HubspotContactService($lead->id);
            $hubspotService->update();
        }
    }
}
