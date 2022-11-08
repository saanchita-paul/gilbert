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
use function Symfony\Component\Translation\t;

class ApplicationServiceStatusService
{
    private $data;
    private $logData;

    const QUOTE_REFERENCE = 'manual_quote_reference';
    const STATUS_SUBMITTED = 4;
    const STATUS_ENERGY_SUBMIT = 12;
    const STATUS_NOT_SUBMITTED = 7;
    const STATUS_CLOSED = 8;

    public static $submitStatues = [
        self::STATUS_SUBMITTED,
        self::STATUS_ENERGY_SUBMIT,
    ];

    public function __construct($data = [], $logData = [])
    {
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
            $data_keys = array_diff(array_keys($data), ['application_status', 'application_id', 'status_reason', 'closed_reason']);

            // Loop through validated keys and save data into DB
            if (count($data_keys) > 0) {
                foreach ($data_keys as $key) {
                    // Explode key to get type and field name
                    $field_key = explode('_', $key);
                    $this->saveServiceStatus($field_key[0], $this->data[$key]);
                }
            } else {
                Log::debug('Manual Status Change: No data to save!');
            }
            $this->saveApplicationStatus();
            if (isset($this->data['application_status']) && $this->data['application_status'] == self::STATUS_CLOSED) {
                $this->saveStatusReason();
            } else {
                $this->setNullStatusReason();
            }
            $this->saveClosedReason();
            UpdateHubspotContactJob::dispatch($this->data['application_id']);
            return $this->getConnectionApplication();
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

    // Save application status
    private function saveApplicationStatus()
    {
        $connectionApplication = $this->getConnectionApplication();

        $this->setManualStatusChangeData($connectionApplication);

        if ($connectionApplication && !is_null($this->data['application_status'])) {
            $connectionApplication->update(['status' => (int)$this->data['application_status']]);
        } else {
            Log::debug('Manual Status Change - No application found to update!');
        }
    }

    // Save service status
    private function saveServiceStatus($service_type, $status_value)
    {
        $connectionApplication = $this->getConnectionApplication();
        if ($connectionApplication && !is_null($status_value)) {
            $service = $connectionApplication->connectionServices()->where('service_type', $service_type)->first();
            if ($service) {
                if (in_array($status_value, self::$submitStatues)) {
                    $service->update(['status' => (int)$status_value, 'quote_reference' => self::QUOTE_REFERENCE]);
                } elseif ($status_value == self::STATUS_NOT_SUBMITTED) {
                    $service->update(['status' => (int)$status_value, 'quote_reference' => null]);
                } else {
                    $service->update(['status' => (int)$status_value]);
                }
            } else {
                Log::debug('Manual Status Change - No connection service found to update!');
            }
        } else {
            Log::debug('Manual Status Change - No application found to update!');
        }
    }

    // Get connection application
    private function getConnectionApplication()
    {
        return ConnectionApplication::find($this->data['application_id']);
    }

    // Make manual status change log data to save in DB
    private function setManualStatusChangeData($connectionApplication)
    {
        $oldStatus = ApplicationServiceStatus::where('status_value', $connectionApplication->status)
            ->where('type', 'application')->first();
        $newStatus = ApplicationServiceStatus::where('status_value', $this->data['application_status'])
            ->where('type', 'application')->first();
        $this->logData['connection_application_id'] = $connectionApplication->id;
        $this->logData['changed_by'] = 1;
        $this->logData['title'] = 'Status Changed by Admin';
        $this->logData['status_change_reason'] = $this->data['status_reason'];
        $this->logData['user_role'] = 'hood_admin';
        $this->logData['data']['old_status']['application'] = $oldStatus->display_text ?? 'N/A';
        $this->logData['data']['new_status']['application'] = $newStatus->display_text ?? 'N/A';
        $this->setConnectionServicesOldStatus($connectionApplication);
        $this->setConnectionServicesNewStatus();
    }

    // Make old status json for services
    private function setConnectionServicesOldStatus($connectionApplication)
    {
        if (count($connectionApplication->connectionServices)) {
            foreach ($connectionApplication->connectionServices as $connectionService) {
                $oldStatus = ApplicationServiceStatus::where('status_value', $connectionService->status)
                    ->where('type', 'service')->first();
                $this->logData['data']['old_status'][$connectionService->service_type] = $oldStatus->display_text ?? 'N/A';
            }
        } else {
            Log::debug('Manual Status Change(OLD status SET) - No connection services found to update!');
        }
    }

    // Make new status json for services
    private function setConnectionServicesNewStatus()
    {
        // Get array_keys of validated data
        $data_keys = array_diff(array_keys($this->data), ['application_status', 'application_id', 'status_reason']);

        // Loop through validated keys and save data into DB
        if (count($data_keys) > 0) {
            foreach ($data_keys as $key) {
                // Explode key to get type and field name
                $field_key = explode('_', $key);
                $newStatus = ApplicationServiceStatus::where('status_value', $this->data[$key])
                    ->where('type', 'service')->first();
                $this->logData['data']['new_status'][$field_key[0]] = $newStatus->display_text ?? 'N/A';
            }
        } else {
            Log::debug('Manual Status Change(New Status SET): No connection services found to update!');
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
            $existingApplication = $this->getConnectionApplication();
            $existingApplication->update([
                'app_close_reason_id' => $this->data['app_close_reason_id'],
                'closing_reason' => null,
                'closed_at' => now(),
                'closed_by' => $user->id,
            ]);

            // get dropdown reason id text
            $applicationReasonIdText = AppCloseReason::select('value')->where('id', $this->data['closed_reason'])->first();

            $allicationNoteService = new ApplicationNoteService($user);
            $closingeNote = [];
            $closingeNote['text'] = 'App closed reason:' . $applicationReasonIdText?->value . (!empty($application['closing_reason']) ? "\n" . 'Additional Notes:' . $application['closing_reason'] : '');
            $closingeNote['type'] = 'close_connection';

            $allicationNoteService->createNotes($closingeNote, $this->data['application_id']);


            return $existingApplication;
        } catch (\Exception $exception) {
            \Log::error("**CloseApplication**",
                ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]);
        }
    }

    // Set closed reason to null
    private function setNullStatusReason()
    {
        try {
            $existingApplication = $this->getConnectionApplication();
            $existingApplication->update([
                'app_close_reason_id' => null,
                'closing_reason' => null,
                'closed_at' => null,
                'closed_by' => null,
            ]);
            return $existingApplication;
        } catch (\Exception $exception) {
            \Log::error("**CloseApplication**",
                ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]);
        }
    }
}
