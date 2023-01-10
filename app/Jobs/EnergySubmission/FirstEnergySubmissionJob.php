<?php

namespace App\Jobs\EnergySubmission;

use App\Models\ConnectionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FirstEnergySubmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $applicationId;
    private string $submitType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $applicationId, string $submitType)
    {
        $this->applicationId = $applicationId;
        $this->submitType = $submitType;
    }

    /**
     * Handle the this.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $submitType = $this->submitType;
        $application = ConnectionApplication::with('connectionServices')->where('id', $this->applicationId)->firstOrFail();
        $error = [];

        $allowedSubmitType = ['energy', 'power', 'gas'];
        if (in_array($submitType, $allowedSubmitType)) {
            try {
                ConnectionApplication::where('id' , $this->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
            }
            catch (\Exception $e) {
                $logError = [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                $error = $e;
                Log::error('FirstEnergySubmission:handle - FAIL (Refer context for details)', $logError);
            }
            finally {
                $application->update([
                    'is_running_submission' => 0,
                ]);

                if (!empty($error)) {
                    throw $error;
                }
            }
        } else {
                info("Skipping First Energy Submit", [
                'submit_type' => $submitType
            ]);
        }
    }
}
