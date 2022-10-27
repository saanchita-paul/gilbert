<?php

namespace App\Services\Application;


use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Log;

class ApplicationServiceStatusService
{
    private $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    // Save single application status
    public function saveStatus()
    {
        // Get array_keys of validated data
        $data_keys = array_diff(array_keys($this->data), ['application_status']);

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
        if ($connectionApplication) {
            $connectionApplication->update(['status' => (int)$this->data['application_status']]);
        } else {
            Log::debug('Manual Status Change - No application found to update!');
        }
    }

    // Save service status
    private function saveServiceStatus($service_type, $status_value)
    {
        $connectionApplication = $this->getConnectionApplication();
        if ($connectionApplication) {
            $service = $connectionApplication->connectionServices()->where('service_type', $service_type)->first();
            if ($service) {
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

    // Save status update reason
    private function saveStatusReason($status_reason)
    {
        return true;
    }
}
