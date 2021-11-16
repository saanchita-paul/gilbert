<?php

namespace Ignite\Commands;

use Ignite\Jobs\IgniteFetchJob;
use Illuminate\Console\Command;
class IgniteFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ignite:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it will fetch leads from ignite';

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
        $this->line('Ignite fetch lead command started successfully!');
        IgniteFetchJob::dispatch();
        $this->line('Ignite fetch lead command finished successfully!');
    }
}
