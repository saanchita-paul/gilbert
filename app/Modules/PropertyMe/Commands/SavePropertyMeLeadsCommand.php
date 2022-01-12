<?php

namespace App\Modules\PropertyMe\Commands;

use App\Models\Office;
use App\Modules\PropertyMe\Services\SaveToConnectionApplication;
use Illuminate\Console\Command;
use PropertyMe\Services\FetchContacts;

class SavePropertyMeLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property_me:save_contact';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
     * @throws \Exception
     */
    public function handle()
    {
        $likedOffice = Office::query()
            ->whereNotNull('property_me_refresh_token')
            ->with('agency')
            ->get();

        foreach ($likedOffice as $office) {
            $this->saveLead($office);
        }
    }

    /**
     * @throws \Exception
     */
    private function saveLead(Office $office): void
    {
        $pm = new FetchContacts($office->property_me_refresh_token);
        $leads = $pm->fetchContacts()->createLead()->getSavedLeads();

        $this->info("New Lead: " . sizeof($leads));

        $saveService = new SaveToConnectionApplication($office);

        foreach ($leads as $lead) {
            $saveService->run($lead);
        }
    }
}
