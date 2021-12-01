<?php

namespace App\Modules\PropertyMe\Services;

use App\Models\ConnectionApplication;
use App\Models\Office;
use PropertyMe\PropertyMeLead;

class SaveToConnectionApplication
{
    public function run(PropertyMeLead $lead)
    {
        $leadData = json_decode($lead->all_fields_dump, true);
        $office = Office::query()->whereName('PropertyMe-Hood-Office')->first();

        throw_if(!$office, new \Exception("PropertyMe-Hood-Office not found. Please run: `php artisan db:seed --class=PropertyMeSeeder`"));

        $application = ConnectionApplication::query()->create([
            'first_name' => $this->extractContact($leadData, 'FirstName'),
            'last_name' => $this->extractContact($leadData, 'LastName'),
            'Email' => $this->extractContact($leadData, 'Email'),
            'office_id' => $office->id,
            'agency_id' => $office->agency->id,
        ]);

        $this->saveApplicationId($application->id, $lead);
    }

    private function extractContact($leadData, $key)
    {
        dd(data_get($leadData, "PrimaryContactPerson.$key") ?? data_get($leadData, "ContactPersons.0.$key"));
        return data_get($leadData, "PrimaryContactPerson.$key") ?? data_get($leadData, "ContactPersons.0.$key");
    }

    private function saveApplicationId(int $id, PropertyMeLead $lead)
    {
        $lead->connection_application_id = $id;
        $lead->save();
    }
}
