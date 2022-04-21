<?php

namespace App\Modules\PropertyMe\Commands;

use Exception;
use Illuminate\Console\Command;
use PropertyMe\Services\SaveAgentEmailService;
use App\Notifications\ErrorLogNotification;
use Illuminate\Support\Facades\Notification;

class SetPropertyMeAgentEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property_me:set_agent_email';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    private $saveAgentEmailService;
    protected array $noAgentLeads = [];

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
        $this->overwriteConfigs();

        $this->saveAgentEmailService = new SaveAgentEmailService();
        $this->getLeads();
        $this->handleNoAgentLeads();
    }

    /** Fetching leads for all offices
     *
     * @throws Exception
     */
    private function getLeads()
    {
        $this->line("Command Execution started\n\n");

        $leadsWithoutAgentEmail = $this->saveAgentEmailService->getLeadsWithoutEmail();

        foreach ($leadsWithoutAgentEmail as $lead) {
            $this->setEmailFromApi($lead);
        }

        $this->line("Command Execution finished");
    }

    /**
     * getting agent email from API
     *
     * @throws Exception
     */

    private function setEmailFromApi($lead): void
    {
        $this->line("Execution started for Lead ID: ". data_get($lead, 'property_me_id'));

        $agentEmail = $this->saveAgentEmailService->getEmailFromApi($lead);

        if ($agentEmail) {
            $this->line("Email found for Lot ID: " . data_get($lead, 'lot_id'));
            $this->saveAgentEmailService->saveAgentEmailToPropertyMeTable(data_get($lead, 'property_me_id'), $agentEmail);
            $this->setAgentIdToApplication($lead, $agentEmail);
        } else {
            $this->line("No Email found for Lot ID: " . data_get($lead, 'lot_id'));
        };
    }

    /**
     * saving agent Id to Connection Application
     *
     * @throws Exception
     */

    private function setAgentIdToApplication($lead, $agentEmail): void
    {
        $agentId = $this->saveAgentEmailService->getAgentID($agentEmail);

        if ($agentId) { 
            $this->line("Agent found with Email: $agentEmail");
            $this->saveAgentEmailService->saveAgentId(data_get($lead, 'connection_application_id'), $agentId);
        } else {
            $this->line("No Agent ID found with Email: $agentEmail");
            $this->noAgentLeads[] = [
                'office_id' => data_get($lead, 'office_id'),
                'Property_me_lead_id' => data_get($lead, 'property_me_id'),
                'connection_application_id' => data_get($lead, 'connection_application_id'),
                'lot_id' => data_get($lead, 'lot_id'),
                'agent_email' => $agentEmail,
            ];
        }

        $this->line(" ");
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
    }

    /**
     * handling failed to save leads
     *
     * @return void
     */
    public function handleNoAgentLeads()
    {
        if (sizeof($this->noAgentLeads) > 0) {
            $this->sendErrorNotification();
        }
    }

    private function sendErrorNotification()
    {
        $error_message = "";
        foreach ($this->noAgentLeads as $lead) {
            $error_message .= "Office: " . $lead['office_id'] . ", PropertyMe Id: " . $lead['Property_me_lead_id'] . ", Application Id: " . $lead['connection_application_id'] . ", Agent Email: " . $lead['agent_email'] . "\n";
        }

        $mgs = "The following Leads do not have any Agent associated with Provided email"
        . "\n\n"
            . "\n{$error_message}";

        $emails = explode(',', config('property_me.support_emails'));

        Notification::route('mail', $emails)->notify(new ErrorLogNotification($mgs, "PropertyMe leads without Agent"));
    }
}
