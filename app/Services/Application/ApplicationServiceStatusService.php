<?php

namespace App\Services\Application;


use App\Jobs\UpdateHubspotContactJob;
use App\Models\AppCloseReason;
use App\Models\ApplicationServiceStatus;
use App\Models\ConnectionApplication;
use App\Models\ManualStatusChangeLog;
use App\Services\Agency\ApplicationNoteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicationServiceStatusService
{
    private $data;
    private $logData;
    private $connectionApplication;

    public const QUOTE_REFERENCE = 'manual_quote_reference';
    public const STATUS_SUBMITTED = 4;
    public const STATUS_ENERGY_SUBMIT = 12;
    public const STATUS_NOT_SUBMITTED = 7;
    public const STATUS_CLOSED = 8;

    public static $submitStatues = [
        self::STATUS_SUBMITTED,
        self::STATUS_ENERGY_SUBMIT,
    ];

    public function __construct($connectionApplication, $data = [], $logData = [])
    {
        $this->connectionApplication = $connectionApplication;
        $this->data = $data;
        $this->logData = $logData;
    }

    // Save single application status
    public function saveStatus()
    {
        try {
            $data = array_filter($this->data, function ($value) {
                return $value != null;
            });

            // Get array_keys of validated data
            $dataKeys = array_diff(
                array_keys($data),
                ['application_status', 'application_id', 'status_reason', 'closed_reason']
            );

            // Loop through validated keys and save data into DB
            foreach ($dataKeys as $key) {
                // Explode key to get type and field name
                $fieldKey = explode('_', $key);
                $this->saveServiceStatus($fieldKey[0], $this->data[$key]);
            }

            $this->saveApplicationStatus();

            if (isset($this->data['application_status']) && $this->data['application_status'] == self::STATUS_CLOSED) {
                $this->saveClosedReason();
            } else {
                $this->setNullStatusReason();
            }

            $this->saveStatusReason();

            UpdateHubspotContactJob::dispatch($this->data['application_id']);
            return $this->connectionApplication;
        } catch (\Exception $e) {
            Log::error('Manual Status Change: ' . $e->getMessage());
            return false;
        }
    }

    // Save bulk application status
    public function saveBulkStatus($items = [])
    {
        if (count($items) > 0) {
            foreach ($items as $item) {
                $this->data = $item;
                $this->saveStatus();
            }
        } else {
            Log::debug('Manual Status Change - No items found to update!');
        }
    }

    /**
     * @throws \Exception
     * Save application status
     */
    private function saveApplicationStatus()
    {
        $this->setManualStatusChangeData();

        if (!is_null($this->data['application_status'])) {
            $this->connectionApplication->update(['status' => (int)$this->data['application_status']]);
        }
    }

    // Save service status
    private function saveServiceStatus($service_type, $status_value)
    {
        $service = $this->connectionApplication->connectionServices()->where('service_type', $service_type)->first();
        if ($service && !is_null($status_value)) {
            if (in_array($status_value, self::$submitStatues)) {
                $service->update(['status' => (int)$status_value, 'quote_reference' => self::QUOTE_REFERENCE]);
            } elseif ($status_value == self::STATUS_NOT_SUBMITTED) {
                $service->update(['status' => (int)$status_value, 'quote_reference' => null]);
            } else {
                $service->update(['status' => (int)$status_value]);
            }
        }
    }

    // Make manual status change log data to save in DB
    private function setManualStatusChangeData()
    {
        $appClosedReason = AppCloseReason::select('value')
            ->where('id', $this->data['closed_reason'])->first();
        $oldStatus = ApplicationServiceStatus::where('status_value', $this->connectionApplication->status)
            ->where('type', 'application')->first();
        $newStatus = ApplicationServiceStatus::where('status_value', $this->data['application_status'])
            ->where('type', 'application')->first();
        $this->logData['connection_application_id'] = $this->connectionApplication->id;
        $this->logData['changed_by'] = 1;
        $this->logData['title'] = 'Status Changed by Admin';
        $this->logData['status_change_reason'] = $this->data['status_reason'];
        $this->logData['user_role'] = 'hood_admin';
        $this->logData['data']['old_status']['application'] = $oldStatus->display_text ?? 'N/A';
        if ($appClosedReason) {
            $this->logData['data']['new_status']['application'] = $newStatus->display_text .
                ' (' . $appClosedReason->value . ')';
        } else {
            $this->logData['data']['new_status']['application'] = $newStatus->display_text ?? 'N/A';
        }
        $this->setConnectionServicesOldStatus($this->connectionApplication);
        $this->setConnectionServicesNewStatus();
    }

    // Make old status json for services
    private function setConnectionServicesOldStatus()
    {
        foreach ($this->connectionApplication->connectionServices as $connectionService) {
            $oldStatus = ApplicationServiceStatus::where('status_value', $connectionService->status)
                ->where('type', 'service')->first();
            $this->logData['data']['old_status'][$connectionService->service_type] = $oldStatus->display_text ?? 'N/A';
        }
    }

    // Make new status json for services
    private function setConnectionServicesNewStatus()
    {
        // Get array_keys of validated data
        $dataKeys = array_diff(array_keys($this->data), ['application_status', 'application_id', 'status_reason']);

        // Loop through validated keys and save data into DB
        foreach ($dataKeys as $key) {
            // Explode key to get type and field name
            $fieldKey = explode('_', $key);
            $newStatus = ApplicationServiceStatus::where('status_value', $this->data[$key])
                ->where('type', 'service')->first();
            $this->logData['data']['new_status'][$fieldKey[0]] = $newStatus->display_text ?? 'N/A';
        }
    }

    // Save status update reason
    private function saveStatusReason()
    {
        return ManualStatusChangeLog::create($this->logData);
    }

    // Save closed reason
    private function saveClosedReason()
    {
        try {
            $user = Auth::user();
            $this->connectionApplication->update([
                'app_close_reason_id' => $this->data['closed_reason'],
                'closing_reason' => null,
                'closed_at' => now(),
                'closed_by' => $user->id,
            ]);

            // get dropdown reason id text
            $applicationReasonIdText = AppCloseReason::select('value')
                ->where('id', $this->data['closed_reason'])->first();

            $allicationNoteService = new ApplicationNoteService($user);
            $closingeNote = [];
            $closingeNote['text'] = 'App closed reason:' . $applicationReasonIdText?->value .
                (
                !empty($application['closing_reason']) ? "\n" .
                    'Additional Notes:' . $application['closing_reason'] : ''
                );
            $closingeNote['type'] = 'close_connection';

            $allicationNoteService->createNotes($closingeNote, $this->data['application_id']);


            return $this->connectionApplication;
        } catch (\Exception $exception) {
            \Log::error(
                "**CloseApplication**",
                ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]
            );
        }
    }

    // Set closed reason to null
    private function setNullStatusReason()
    {
        try {
            $this->connectionApplication->update([
                'app_close_reason_id' => null,
                'closing_reason' => null,
                'closed_at' => null,
                'closed_by' => null,
            ]);
            return $this->connectionApplication;
        } catch (\Exception $exception) {
            \Log::error(
                "**CloseApplication**",
                ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]
            );
        }
    }
}
