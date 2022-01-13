<?php

namespace App\Modules\PropertyMe\Commands;

use App\Models\Office;
use App\Modules\PropertyMe\Services\SaveToConnectionApplication;
use Exception;
use Illuminate\Console\Command;
use PropertyMe\Services\FetchContacts;
use PropertyMe\Services\SaveContacts;

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
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $linkedOffice = Office::query()
            ->whereNotNull('property_me_refresh_token')
            ->with('agency')
            ->get();

        foreach ($linkedOffice as $office) {
            $this->saveLead($office);
        }
    }

    /**
     * @throws Exception
     */

    private function saveLead(Office $office): void
    {
        $pm = new SaveContacts($office->property_me_refresh_token);
        $leads = $pm->fetch()->createLead()->getSavedLeads();
        $lots = $pm->getLots();

        $this->info("New Lead: " . sizeof($leads));

        $saveService = new SaveToConnectionApplication($office, $lots);

        foreach ($leads as $lead) {
            $saveService->run($lead);
        }
    }
}
