<?php

namespace App\Services\Application;


use App\Models\ApplicationServiceStatus;
use App\Models\ConnectionApplication;
use App\Models\ManualStatusChangeLog;
use Illuminate\Support\Facades\Log;

class ApplicationServiceStatusService
{
    private $data;
    private $logData;

    public function __construct($data = [], $logData = [])
    {
        $this->data = $data;
        $this->logData = $logData;
    }

    // Save single application status
    public function saveStatus()
    {
        // Get array_keys of validated data
        $data_keys = array_diff(array_keys($this->data), ['application_status', 'application_id', 'status_reason']);

//        dd($this->data, $data_keys);
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
        $this->saveStatusReason();
        return $this->getConnectionApplication();
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
            dd($service_type, $status_value);
            $service = $connectionApplication->connectionServices()->where('service_type', $service_type)->first();
            if ($service) {
                $newStatus = $status_value ? ApplicationServiceStatus::where('status_value', $status_value)
                    ->where('type', 'service')->first()->display_text : 'N/A';
                $this->logData['data']['new_status'][$service_type] = $newStatus;
                $service->update(['status' => (int)$status_value]);
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
    }

    private function setConnectionServicesOldStatus($connectionApplication)
    {
        if (count($connectionApplication->connectionServices)) {
            foreach ($connectionApplication->connectionServices as $connectionService) {
                $oldStatus = ApplicationServiceStatus::where('status_value', $connectionService->status)
                    ->where('type', 'service')->first();
                $this->logData['data']['old_status'][$connectionService->service_type] = $oldStatus->display_text ?? 'N/A';
            }
        } else {
            Log::debug('Manual Status Change - No connection services found to update!');
        }
    }

    // Save status update reason
    private function saveStatusReason()
    {
        ManualStatusChangeLog::create($this->logData);
    }
}
