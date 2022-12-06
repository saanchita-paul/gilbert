<?php

namespace App\Console\Commands;

use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotHandlerService;
use Illuminate\Console\Command;

/**
 *
 */
class CreateHubSpotContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:create {--id=} {--days=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create hubspot contact from applications';

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
     * @return void
     */
    public function handle(): void
    {
        if ($this->option('id') !== null) {
            $this->createOne();
        } else {
            $this->createMany();
        }
    }

    /**
     * @return void
     */
    private function createMany(): void
    {
        if (empty($this->option('days'))) {
            $this->error("Either application id or numbers days need to provided: Ex: --id=1213 or --days=13");
            return;
        }

        $days = (int) $this->option('days');

        $apps = ConnectionApplication::query()
            ->select('id')
            ->whereNull('hubspot_contact_id')
            ->where('created_at', '>=', today()->subDays($days))
            ->pluck('id')
            ->toArray();
        $this->info(count($apps) . " applications found!");
        foreach ($apps as $app) {
            $this->createcontact($app);
        }

        $this->info("Finished!!");
    }

    /**
     * @return void
     */
    private function createOne(): void
    {
        $app = ConnectionApplication::query()
            ->select('id')
            ->whereNull('hubspot_contact_id')
            ->where('id', $this->option('id'))
            ->first();
        if (!$app) {
            $this->error("Can't find and app with id: {$this->option('id')} that does not have Hubspot reference ID.");
        } else {
            $this->createcontact($app->id);
        }
    }

    /**
     * @param int $appId
     * @return void
     */
    private function createContact(int $appId): void
    {
        $handler = new HubspotHandlerService($appId);
        try {
            $handler->handle();
            $this->info("Completed: $appId");
        } catch (\Exception $e) {
            $this->error("Failed: $appId, Reason: {$e->getMessage()}");
        }
    }
}
