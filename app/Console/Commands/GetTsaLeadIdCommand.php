<?php

namespace App\Console\Commands;

use App\Models\ConnectionApplication;
use Illuminate\Console\Command;
use TSA\Services\TsaCallHistoryService;
use TSA\Services\TsaSendAppliationService;

class GetTsaLeadIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:get-tsa-lead-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get TSA Lead Id';

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
        $applications = ConnectionApplication::query()
            ->whereNotNull('tsa_id')
            ->whereNull('tsa_lead_id')
            ->get();

//        dump($applications->count());
        foreach ($applications as $application) {

            $tsaService = new TsaSendAppliationService($application->id);
            $lead_id = $tsaService->getTsaLeadId();

            info('TSA lead id in scheduler after api call->', [$lead_id]);

            ConnectionApplication::where('id', $application->id)
                ->update([
                    'tsa_lead_id' => $lead_id
                ]);
        }
    }
}
