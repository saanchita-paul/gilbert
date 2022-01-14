<?php

namespace App\Modules\PropertyMe\Services;

use App\Models\Identification;
use Illuminate\Support\Facades\Log;
use App\Services\Logger\ErrorLogService;

class DobIdentificationService
{
    const STATE_NSW = "New South Wales";
    const STATE_VIC = "Victoria";
    const STATE_QLD = "Queensland";
    const STATE_SA = "South Australia";
    const STATE_NT = "Northern Territory";
    const STATE_TAS = "Tasmania";
    const STATE_ACT = "Australian Capital Territory";
    const STATE_WA = "Western Australia";

    const Country_AUS = "Australia";
    
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get()
    {
        if(trim($this->data) === null || trim($this->data) === '') {
            Log::info('PropertyMe (Save Contact): Note is empty');
            return null;
        }

        $single_n_data = preg_replace("/[\r\n]+/", "\n", $this->data);
        $data_array = array_chunk(explode("\n", $single_n_data), 2);

        $raw_person_data = array_key_exists(0, $data_array) ? $data_array[0] : null;
        $raw_authorised_person_data = array_key_exists(1, $data_array) ? $data_array[1] : null;

        if($this->validatePersonData($raw_person_data)){
            $person_data = $this->getPersonData($raw_person_data, 'person');
        } else {
            $person_data = null;
            Log::error('PropertyMe (Save Contact): Note data is invalid', [$this->data]);
            ErrorLogService::send(
                'PropertyMe (Save Contact): Note data is invalid',
                []
            );
        }

        if (is_array($raw_authorised_person_data) && count($raw_authorised_person_data) >= 1) {
            if($this->validateAuthorizedPersonData($raw_authorised_person_data)){
                $authorised_person_data = $this->getPersonData($raw_authorised_person_data, 'authorised_person');
            } else {
                $authorised_person_data = null;
                Log::error('PropertyMe (Save Contact): Note data for Authorized person is invalid', [$this->data]);
                ErrorLogService::send(
                    'PropertyMe (Save Contact): Note data for Authorized person is invalid',
                    []
                );
            }
        }
        else {
            $authorised_person_data = null;
        }

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
            'state' => $type === 2 ? $this->mapState($identification_data[2]) : null,
            'country' => $type === 1 ? $this->mapCountry($identification_data[2]) : null,
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

    private function mapState(?string $state)
    {
        return match($state) {
            'NSW' => self::STATE_NSW,
            'VIC' => self::STATE_VIC,
            'QLD' => self::STATE_QLD,
            'SA' => self::STATE_SA,
            'NT' => self::STATE_NT,
            'TAS' => self::STATE_TAS,
            'ACT' => self::STATE_ACT,
            'WA' => self::STATE_WA,
            default => null
        };
    }

    private function mapCountry(?string $country)
    {
        return match($country) {
            'AUS' => self::Country_AUS,
            default => null
        };
    }

    private function validatePersonData($data)
    {
        if (!is_array($data) || count($data) < 2) {
            return false;
        }
        else {
            if (!str_contains($data[0], 'DOB')
                || !preg_match('/\d{2}\/\d{2}\/\d{4}/', $data[0])) {
                return false;
            }
            if (!(str_contains($data[1], 'Passport') || str_contains($data[1], 'DL'))
                || count(explode(' ', $data[1])) < 4) {
                return false;
            }
            if (!$this->containState($data[1])) {
                return false;
            }
        }
        return true;
    }

    private function validateAuthorizedPersonData($data)
    {
        if (!str_contains($data[0], 'DOB')
            || !preg_match('/\d{2}\/\d{2}\/\d{4}/', $data[0])) {
            return false;
        }
        return true;
    }

    private function containState($data)
    {
        $state_words = [
            'NSW', 'VIC', 'QLD', 'SA', 'NT', 'TAS', 'ACT', 'WA', 'AUS'
        ];
        foreach ($state_words as $word) {
            if (str_contains($data, $word)) {
                return true;
            }
        }
        return false;
    }

}
