<?php

namespace GoogleAds\Commands;

use GoogleAds\Services\FetchGCLService;
use GoogleAds\Services\ManageClickConversion;
use Exception;
use Illuminate\Console\Command;

class ClickConversionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:adds:click:upload {--nofetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fetch connection applications and hubspot data to check if there is gclid';


    /**
     * @throws Exception
     */
    public function handle(): void
    {
        if (!$this->option('nofetch')) {
            #refetching
            FetchGCLService::start();
        }

        #uploading
        ManageClickConversion::upload();
    }

}
