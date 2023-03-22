<?php

namespace App\Console\Commands\hubspot;

use App\Services\hubspot\HubspotService;
use Illuminate\Console\Command;

class SendAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:fetch-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fetch connection applications and hubspot data to check if there is gclid';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $hubspotService = new HubspotService();
        return 0;
    }
}
