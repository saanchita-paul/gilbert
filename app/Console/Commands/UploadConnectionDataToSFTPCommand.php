<?php

namespace App\Console\Commands;

use App\Services\Agency\CafFile\ProcessAndUploadConnectionToSFTPService;
use Illuminate\Console\Command;

class UploadConnectionDataToSFTPCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ea:upload:lead';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload lead data to EA through Caf file';

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
        $service = new ProcessAndUploadConnectionToSFTPService();
        $service->processConnectionData();

        return 0;
    }
}
