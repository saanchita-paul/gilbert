<?php

namespace App\Console\Commands\hubspot;

use App\Services\hubspot\FetchGCLService;
use Google\ApiCore\ApiException;
use Illuminate\Console\Command;

class UploadClickConversionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:upload-click';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fetch connection applications and hubspot data to check if there is gclid';


    public function handle(): int
    {
        $fetchGCLService = new FetchGCLService();
        $applications = $fetchGCLService->fetchConnectionApplications();
        $fetchGCLService->fetchGclId($applications);
//        $fetchGCLService->uploadClickConversion();
        return 0;
    }
}
