<?php

namespace App\Jobs;

use App\Services\hubspot\HubspotContactService;
use App\Services\Sales\GetSalesRequestStaus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSaleApiLeadData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $ea_sales_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,  $ea_sales_id)
    {
        $this->id = $id;
        $this->ea_sales_id = $ea_sales_id;
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
            $service->getSalesStatus( $this->ea_sales_id, $this->id);
            $hubspotService = new HubspotContactService($this->id);
            $hubspotService->update();

    }
}
