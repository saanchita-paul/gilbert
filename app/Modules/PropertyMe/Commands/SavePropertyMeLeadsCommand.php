<?php

namespace App\Modules\PropertyMe\Commands;

use App\Models\Office;
use App\Modules\PropertyMe\Services\SaveToConnectionApplication;
use App\Notifications\ErrorLogNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use PropertyMe\Services\SaveContacts;

class SavePropertyMeLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property_me:save_contact {--office=} {--days=}';
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

    /**
     * leads that are saved in property_me_leads table,
     * but failed to save in connection_application table.
     *
     * @var array
     */
    protected array $failedLeads = [];

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
        $this->overwriteConfigs();

        if ($this->option('office') !== null) {
           $this->fetchForSelectedOffice();
        } else {
            $this->fetchForAllOffices();
        }

        $this->handleFailedLeads();
    }


    /**
     * @throws Exception
     */
    private function fetchForSelectedOffice()
    {
        $office = Office::where('id', $this->option('office'))
            ->whereNotNull('property_me_refresh_token')
            ->first();


        if (!$office) {
            $this->error("No PropertyMe connected office found with id: {$this->option('office')}");
            die();
        }

        $this->saveLead($office);
    }

    /** Fetching leads for all offices
     *
     * @throws Exception
     */
    private function fetchForAllOffices()
    {
        $linkedOffice = Office::query()
            ->whereNotNull('property_me_refresh_token')
            ->with('agency')
//            ->orderBy('id', 'desc')->limit(2)
            ->get();

        foreach ($linkedOffice as $office) {
            $this->saveLead($office);
        }
    }

    /**
     * saving leads to connection_applications
     *
     * @param Office $office
     *
     * @throws Exception
     */

    private function saveLead(Office $office): void
    {

        $this->line("[$office->name] START");

        $pm = new SaveContacts($office->property_me_refresh_token);
        $leads = $pm->fetch()->createLead()->getSavedLeads();


        $this->line("[$office->name]  Saved in property_me_leads: " . sizeof($leads));

        $saveService = new SaveToConnectionApplication($office);

        foreach ($leads as $lead) {
            try {
                $saveService->run($lead);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());

                $this->failedLeads[] = [
                    'property_me_lead_id' => $lead->id,
                    'office_name' => $office->name,
                    'office_id' => $office->id,
                    'errMessage' => $e->getMessage(),
                ];
            }
        }
        $this->info("[$office->name] Complete");
    }


    /**
     * handling failed to save leads
     *
     * @return void
     */
    public function handleFailedLeads()
    {
        if (sizeof($this->failedLeads) > 0) {
            $this->error("the following leads failed to save in connection_applications");
            dump($this->failedLeads);
            Log::error("PropertyMe leads that failed to save in connection_applications", $this->failedLeads);
            $this->sendErrorNotification();
        }
    }

    /**
     * Overwrite the configs
     */
    private function overwriteConfigs()
    {
        $time_out = env('SERVER_TIMEOUT', 600);
        $memory_limit = env('SERVER_MEMORY_LIMIT', '1024M');

        \Http::timeout($time_out);
        ini_set('memory_limit', $memory_limit );


        $days = $this->option('days');
        if ($days) {
            config(['property_me.no_of_days' => $days]);
        }
    }

    private function sendErrorNotification()
    {
        $ids = collect($this->failedLeads)->pluck('property_me_lead_id')->implode(', ');
        $mgs = "System is failed to save some leads in connection_applications"
            . "\n\n"
            . "\n[Properties for debugging]\n"
            . "\nServer url: " . config('app.url')
            . "\nServer Time: " . now()->toDateTimeString()
            . "\nTable: property_me_leads"
            . "\nColumn: id"
            ."\nValues: <strong>[{$ids}]<strong>";

        $emails = explode(',', config('property_me.support_emails'));

        Notification::route('mail', $emails)->notify(new ErrorLogNotification($mgs, "Failed to save PropertyMe leads"));
    }
}
