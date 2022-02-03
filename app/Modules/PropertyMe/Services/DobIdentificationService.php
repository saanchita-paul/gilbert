<?php

namespace App\Modules\PropertyMe\Services;

use App\Models\Identification;
use Illuminate\Support\Facades\Log;
use App\Services\Logger\PropertyMeNoteLogService;
use Carbon\Carbon;

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
    private $leadId;
    private $firstName;
    private $lastName;
    private $validationType = null;
    private $invalidNoteData = null;

    public function __construct($data, $leadId, $firstName, $lastName)
    {
        $this->data = $data;
        $this->leadId = $leadId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function get()
    {
        if(trim($this->data) === null || trim($this->data) === '') {
            Log::error('PropertyMe (Save Contact): Note is empty');
            $this->sendEmptyEmail();
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
            $this->validationType = 'person';
            // $this->sendInvalidEmail();
        }

        if (is_array($raw_authorised_person_data) && count($raw_authorised_person_data) >= 1) {
            if($this->validateAuthorizedPersonData($raw_authorised_person_data)){
                $authorised_person_data = $this->getPersonData($raw_authorised_person_data, 'authorised_person');
            } else {
                $authorised_person_data = null;
                Log::error('PropertyMe (Save Contact): Note data for Authorized person is invalid', [$this->data]);
                $this->validationType = $this->validationType === 'person' ? 'both' : 'authorised_person';
                // $this->sendInvalidEmail();
            }
        }
        else {
            $authorised_person_data = null;
        }

        if($this->validationType !== null){
            $this->invalidNoteData = $single_n_data;
            $this->sendInvalidEmail();
        }

        return [
            'person' => $person_data,
            'authorised_person' => $authorised_person_data,
            'invalid_note_data' => $this->invalidNoteData
        ];
    }

    private function getPersonData(?array $data, ?string $type)
    {
        $raw_dob_data = collect($data[0])->filter(function ($item) {
            return str_contains($item, 'DOB');
        })->first();

        $raw_identification_data = collect($data[1])->filter(function ($item) {
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
        $format = $type === 'person' ? 'Y-m-d' : 'Y-m-d 00:00:00';
        return Carbon::createFromFormat('d/m/Y', $raw_dob)->format($format);
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

            $data[1] = preg_split('/\s+/', $data[1]);
            
            if ((!is_array($data[1]) || count($data[1]) < 4)
                ||!(str_contains($data[1][0], 'Passport') || str_contains($data[1][0], 'DL'))
                || !$this->containState($data[1][2])) {
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

    private function sendInvalidEmail()
    {
        $title = $this->validationType === 'authorised_person' ?
            'PropertyMe (Save Contact): Note data for Authorized Person is invalid'
            : 'PropertyMe (Save Contact): Note data is invalid';
            
        PropertyMeNoteLogService::send(
            [
                'title' => $title,
                'tenant_name' => $this->firstName . ' ' . $this->lastName,
                'lead_id' => $this->leadId,
                'expected_format' => "DOB: DD/MM/YYY - Contact first name\nPassport: PAXXXXX Country - Contact first name\nDOB: DD/MM/YYY - Contact first name\nDL: 02132111 State - Contact first name",
                'actual_format' => $this->data,
            ],
            []
        );
    }

    private function sendEmptyEmail()
    {
        PropertyMeNoteLogService::send(
            [
                'title' => 'PropertyMe (Save Contact): Note is empty',
                'tenant_name' => $this->firstName . ' ' . $this->lastName,
                'lead_id' => $this->leadId,
                'expected_format' => "DOB: DD/MM/YYY - Contact first name\nPassport: PAXXXXX Country - Contact first name",
                'actual_format' => $this->data,
            ],
            []
        );
    }
}
