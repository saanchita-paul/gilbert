<?php

namespace App\Modules\PropertyMe\Services;

use App\Models\Identification;

class DobIdentificationService
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get()
    {
        $data_array = array_chunk(explode("\n", $this->data), 2);

        $raw_person_data = array_key_exists(0, $data_array) ? $data_array[0] : null;
        $raw_authorised_person_data = array_key_exists(1, $data_array) ? $data_array[1] : null;

        $person_data = $this->getPersonData($raw_person_data, 'person');
        $authorised_person_data = $this->getPersonData($raw_authorised_person_data, 'authorised_person');

        return [
            'person' => $person_data,
            'authorised_person' => $authorised_person_data,
        ];
    }

    private function getPersonData(?array $data, ?string $type)
    {
        $raw_dob_data = collect($data)->filter(function ($item) {
            return str_contains($item, 'DOB');
        })->first();

        $raw_identification_data = collect($data)->filter(function ($item) {
            return str_contains($item, 'Passport') || str_contains($item, 'DL');
        })->first();

        $dob_data = $this->getDateOfBirthData($raw_dob_data, $type);
        $identification_data = $this->getIdentificationData($raw_identification_data);

        return [
            'dob' => $dob_data,
            'identification' => $identification_data,
        ];
    }

    private function getDateOfBirthData(?string $raw_dob, ?string $type)
    {
        $raw_dob = trim($raw_dob);
        preg_match('/\d{2}\/\d{2}\/\d{4}/', $raw_dob, $matches);

        return array_key_exists(0, $matches) ? $this->getFormattedDate($matches[0], $type) : null; 
    }

    private function getFormattedDate(string $raw_dob, string $type)
    {
        $format = $type === 'person' ? 'Y/m/d' : 'Y/m/d H:i:s';
        return date($format, strtotime($raw_dob));
    }

    private function getIdentificationData(?string $raw_identification)
    {
        $raw_identification = trim($raw_identification);
        $identification_data = preg_split('/\s+/', $raw_identification);
        $type = $this->getIdentificationType($identification_data[0]);
        
        return [
            'type' => $type,
            'card_number' => $identification_data[1] ?? null,
            'state' => $type === 2 ? $identification_data[2] : null,
            'country' => $type === 1 ? $identification_data[2] : null,
        ];
    }

    private function getIdentificationType(?string $type)
    {
        if (str_contains($type, 'Passport')) {
            return Identification::TYPE_PASSPORT;
        } elseif (str_contains($type, 'DL')) {
            return Identification::TYPE_DRIVING_LICENCE;
        }
        return null;
    }
}
