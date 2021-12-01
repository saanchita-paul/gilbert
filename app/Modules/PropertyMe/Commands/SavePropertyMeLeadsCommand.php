<?php

namespace App\Modules\PropertyMe\Commands;

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
        $pm = new FetchContacts();
        $leads = $pm->fetchContacts()->createLead()->getSavedLeads();
        $this->info("New Lead: " . sizeof($leads));

        $saveService = new SaveToConnectionApplication();

        foreach ($leads as $lead) {
            $saveService->run($lead);
        }
    }
}
