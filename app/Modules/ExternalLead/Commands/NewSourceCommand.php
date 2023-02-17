<?php

namespace ExternalLead\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\ExternalSource;
use App\Models\Office;
use Illuminate\Support\Facades\Hash;

class NewSourceCommand extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external:new';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new external source';

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
     * @throws Exception
     */
    public function handle()
    {
        $defaultOffices = Office::doesntHave('externalSource')
                            ->where('is_default_office', true)
                            ->get();

        if (count($defaultOffices) == 0) {
            $this->line('No available default office to create new external source. Please check for missing seeder');
            return;
        }

        $this->line('Available options:');
        foreach ($defaultOffices as $key => $val) {
            $this->line(sprintf("%s => %s", $key, $val['name']));
        }

        $selectedOffice = false;

        while (!$selectedOffice) {
            $optionInput = $this->ask('Please input the number you wish to create a new external source');
            $selectedOffice = $defaultOffices[$optionInput] ?? false;
        }

        $this->line('Selected: ' . $selectedOffice->name);

        $userEmail = '';
        $password = '';
        $confirmPass = '';

        while (empty($userEmail)) {
            $userEmail = $this->ask('Please input the user email');
        }

        $this->line('Successfully set email: ' . $userEmail);

        while (empty($password) || $password != $confirmPass) {
            $password = $this->secret('Please input the user password');
            $confirmPass = $this->secret('Please input the confirm password');

            if ($password != $confirmPass) {
                $this->line('Password and confirm password does not match');
            }
        }

        $this->line('Successfully set password');

        $sourceType = '';

        while (empty($sourceType) || ExternalSource::where('source_type', $sourceType)->exists()) {
            $sourceType = $this->ask('Please input the source type name (example: tapp)');

            if (ExternalSource::where('source_type', $sourceType)->exists()) {
                $this->line("Source type $sourceType already exists");
            }
        }

        $this->line('Successfully set source type: ' . $sourceType);

        $sourceNameDisplay = $this->ask("(OPTIONAL) Please input the source name for display purpose");

        $this->line("Successfully set source display name: " . (empty($sourceNameDisplay) ? ucwords($sourceType) : $sourceNameDisplay));

        $newExternalSource = new ExternalSource();
        $newExternalSource->email = $userEmail;
        $newExternalSource->password = Hash::make($password);
        $newExternalSource->is_active = true;
        $newExternalSource->default_office_id = $selectedOffice->id;
        $newExternalSource->source_type = $sourceType;

        if (!empty($sourceNameDisplay)) {
            $newExternalSource->display_type_name = $sourceNameDisplay;
        }

        $newExternalSource->save();

        $this->line('Successfully created a new external source with ID ' . $newExternalSource->id);
    }
}
