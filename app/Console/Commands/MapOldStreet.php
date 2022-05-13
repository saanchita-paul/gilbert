<?php

namespace App\Console\Commands;

use App\Services\SeparateStreetNameService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MapOldStreet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map_old_street_name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        ini_set('memory_limit', "1024m" );

        $service = new SeparateStreetNameService();
        $result = $service->updateOldData();

        foreach (array_chunk($result['failingLeads'], 100) as $key => $chunk) {
            $this->log("Failed chunk ${key}: ", $chunk);
        }
//        $this->table(['Lead id', 'street name'], $result['failingLeads']);
        $this->newLine();
        $this->info('Success Count: ' .  sizeof($result['successesStreetMappedLeads']));
        $this->newLine();
        $this->error('Failed Count: ' .  sizeof($result['failingLeads']));
        $this->info("Check storage/logs/custom/street_mapping.log for more details.");
        return 0;
    }


    public function log(?string $message, $extra = [])
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/custom/street_mapping.log'),
        ])->info($message, $extra);
    }

}
