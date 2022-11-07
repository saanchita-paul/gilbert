<?php

namespace App\Console\Commands;

use App\Services\MRI\MriOfficeSendEmailService;
use Illuminate\Console\Command;

class MRIOfficeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-email-mri-office';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email if new MRI office created';

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
    public function handle(): int
    {
        $service = new MriOfficeSendEmailService();
        $service->sendEmail();
        \Log::info("Cron is working fine!");
        return 0;
    }
}
