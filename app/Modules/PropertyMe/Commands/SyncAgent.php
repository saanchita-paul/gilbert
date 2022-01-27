<?php

namespace App\Modules\PropertyMe\Commands;

use App\Models\Office;
use App\Modules\PropertyMe\Services\SaveToConnectionApplication;
use App\Modules\PropertyMe\Services\SyncAgentService;
use Exception;
use Illuminate\Console\Command;
use PropertyMe\Services\SaveContacts;

class SyncAgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property_me:sync_agent';
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
        (new SyncAgentService())->sync();
    }


}
