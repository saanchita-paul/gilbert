<?php

namespace GoogleAds\Commands;

use GoogleAds\Services\ManageCallConversion;
use GoogleAds\Services\SaveCallConversion;
use Exception;
use Illuminate\Console\Command;

class CallConversionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:adds:call:upload {--nofetch}';

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
            SaveCallConversion::save();
        }

        #uploading
        ManageCallConversion::upload();
    }

}
