<?php

namespace Origin\Commands;

use App\Services\Application\ApplicationServiceStatusService;
use Illuminate\Console\Command;
use App\Models\ConnectionService;
use App\Jobs\OriginStatusUpdateJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class OriginCheckStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'origin:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check origin lead statuses and update database if there are any changes';

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
        $this->line('Origin check command started!');
        $services = ConnectionService::where('provider_name', ConnectionService::PROVIDER_ORIGIN)
            ->whereNotNull('lead_reference')
            ->where('status', ConnectionService::STATUS_SUBMITTED)
            ->where(function (Builder $b) {
                $b->where('quote_reference', '!=', ApplicationServiceStatusService::QUOTE_REFERENCE)
                    ->orWhereNull('quote_reference');
            })
            ->get();

        foreach ($services as $service) {
            OriginStatusUpdateJob::dispatch($service->lead_reference, $service->connection_application_id);
        }

        $this->line('Origin fetch plan lead command finished successfully!');
    }
}
