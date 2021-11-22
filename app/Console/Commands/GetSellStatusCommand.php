<?php

namespace App\Console\Commands;

use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotContactService;
use App\Services\Sales\GetSalesRequestStaus;
use Illuminate\Console\Command;

class GetSellStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:sales:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get every sales status by id';

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
        $this->checkStatus();
        return 0;
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
