<?php

namespace App\Console\Commands;

use App\Services\SeparateStreetNameService;
use Illuminate\Console\Command;

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

        $service = new SeparateStreetNameService();
        $result = $service->updateOldData();

        $this->line('Failing Address Street Mapping');
        $this->table(['Lead id', 'street name'], $result['failingLeads']);
        $this->newLine(2);
        $this->line('Successfully Street Mapped connections id');
        $this->line(implode(', ', $result['successesStreetMappedLeads']));

        return 0;
    }
}
