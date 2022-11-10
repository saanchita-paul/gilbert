<?php

namespace MRI\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use MRI\Services\GetAgentService;

class MriFetchAgentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $afterDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($afterDate = '')
    {
        $this->afterDate = $afterDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $agentService = new GetAgentService();
            if (!empty($this->afterDate)){
                $agentService->setAfterDate($this->afterDate);
            }
            $agentService->run();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}