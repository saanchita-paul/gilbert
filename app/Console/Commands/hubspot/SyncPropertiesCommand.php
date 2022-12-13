<?php

namespace App\Console\Commands\hubspot;

use App\Models\ConnectionApplication;
use App\Services\hubspot\HubspotHandlerService;
use App\Services\hubspot\SyncPropertiesService;
use Illuminate\Console\Command;

use function App\Console\Commands\count;

/**
 *
 */
class SyncPropertiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:sync {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync properties from a json';

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
     */
    public function handle(): void
    {
        $path = $this->argument('path');
        (new SyncPropertiesService())->run($path);
    }
}
