<?php

namespace App\Console\Commands;

use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotContactService;
use Exception;
use Illuminate\Console\Command;

class UpdateHubspotContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:update_contacts {--app=} {--after=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update hubspot contacts';

    /**
     * @var array $applications that are failed to update
     */
    protected array $failed = [];

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
     * @throws Exception
     */
    public function handle()
    {
        $appId = $this->option('app');

        if (empty($appId)) {
            $this->handleAll();
        } else {
            $this->handleSelected();
        }
    }

    /**
     * @throws Exception
     */
    private function handleAll()
    {
        $after = (int) $this->option('after');

        $apps = ConnectionApplication::query()
            ->select(['id', 'hubspot_contact_id', 'first_name', 'last_name'])
            ->whereNotNull('hubspot_contact_id')
            ->where('id', '>', $after)
            ->get();

        $this->info('Operation Started...');

        $this->withProgressBar($apps, function ($app) {
            try {
                $this->update($app);

            } catch (Exception $e) {
                $this->failed[] = [
                    'id' => $app->id,
                    'name' => $app->first_name . ' ' . $app->last_name,
                    'error' => $e->getMessage(),
                ];
            }
        });

        $this->newLine();
        $this->info('Operation completed!');
        $this->showFailedJob();
    }

    /**
     * @throws Exception
     */
    private function update(ConnectionApplication $app)
    {
        if ($app->id % 20 === 0) {
            throw new Exception('Manual Error for testing');
        }
        $hubspotContactService = new HubspotContactService($app->id);
        $hubspotContactService->update();
    }

    private function handleSelected()
    {
        $this->warn('Sorry, Not implemented yet');
    }

    private function showFailedJob()
    {
        $this->newLine();
        $this->newLine();

        if (sizeof($this->failed) > 0) {
            $this->error('Some applications is failed to update');
            $this->table(['id', 'name', 'error'], $this->failed);
        }
    }
}
