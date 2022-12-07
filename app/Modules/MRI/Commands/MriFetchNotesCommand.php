<?php

namespace MRI\Commands;

use Exception;
use Illuminate\Console\Command;
use MRI\Services\MriServices;

class MriFetchNotesCommand extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mri:fetch_notes {--office=} {--afterDate=} {--dump}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Agents Data from MRI';

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
        $officeId = $this->option('office') ?? '';
        $afterDate = $this->option('afterDate') ?? '';
        $dump = $this->option('dump');

        $this->line('MRI fetch notes command started successfully!');
        try {
            MriServices::handleFetchNotes($officeId, $afterDate);
        } catch (\Exception $exception) {
            if ($dump) {
                dump($exception->getMessage());
            }
        }
        try {
            MriServices::handleMapNotes();
        } catch (\Exception $exception) {
            if ($dump) {
                dump($exception->getMessage());
            }
        }
        $this->line('MRI fetch notes command finished successfully!');
    }
}
