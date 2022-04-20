<?php

namespace App\Modules\PropertyMe\Commands;

use Exception;
use Illuminate\Console\Command;
use PropertyMe\Services\SaveAgentEmailService;

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
}
