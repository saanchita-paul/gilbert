<?php

namespace App\Modules\PropertyMe\Commands;

use App\Models\Office;
use App\Modules\PropertyMe\Services\SaveToConnectionApplication;
use Exception;
use Illuminate\Console\Command;
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
        $time_out = env('SERVER_TIMEOUT', 600);
        $memory_limit = env('SERVER_MEMORY_LIMIT', '1024M');

        \Http::timeout($time_out);
        ini_set('memory_limit', $memory_limit );

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

        $this->line("[$office->name] START");

        $pm = new SaveContacts($office->property_me_refresh_token);
        $leads = $pm->fetch()->createLead()->getSavedLeads();
        $tenancies = $pm->getTenancies();

        $this->line("[$office->name]  Saved in property_me_leads: " . sizeof($leads));

        $saveService = new SaveToConnectionApplication($office, $tenancies);

        foreach ($leads as $lead) {
            $saveService->run($lead);
        }

        $this->info("[$office->name] Complete");
    }
}
