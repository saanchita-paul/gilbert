<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SlowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public int $id)
    {
        $this->onQueue('fc-address');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        \Log::warning('SlowJob:Start: ' . $this->id);
        sleep(20);
        \Log::warning('SlowJob:Done: ' . $this->id);
    }
}
