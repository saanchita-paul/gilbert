<?php

namespace App\Console\Commands\GoogleAds;

use App\Services\Genesys\SaveCallConversion;
use App\Services\GoogleAds\UploadCallConversion;
use App\Services\GoogleAds\UploadClickConversion;
use App\Services\hubspot\FetchGCLService;
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
        UploadCallConversion::upload();
    }

}
