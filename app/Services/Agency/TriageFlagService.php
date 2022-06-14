<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Log;

class TriageFlagService
{
    const MANDATORY_APP_FIELDS_NOT_HOOD_AI = ['title', 'first_name', 'last_name', 'phone', 'email', 'dob', 'unit_number', 'street_address', 'street_number', 'street_name_only', 'street_type', 'address_text ', 'city', 'postcode', 'state', 'country', 'nmi'];

    const IDENTIFICATION_FIELDS_HOOD_AI = ['type', 'card_number'];

    public static function setTriageFlag(int $applicationId): bool
    {
        return (new static())->checkTriageFlag($applicationId);
    }

    /** check applications missing specific data to have a 'triage' flag
     *
     * @param int $applicationId
     */
    public function checkTriageFlag(int $applicationId): bool
    {
        $details = ConnectionApplication::query()->with('identification')->where('id', $applicationId)->first();

        if ($details->source !== ConnectionApplication::SOURCE_HOOD_LEAD) {
            $res  = $this->checkIfNotHoodLead($details);
        } else {
            $res = $this->checkIfHoodLead($details);
        }

        if ($res && is_null($details->moving_date)) {
            ConnectionApplication::query()->where('id', $applicationId)->update(['moving_date' => date('2025-05-05')]);
        }

        $this->setTriage($details->id, $res);

        return $res;
    }


    private function checkIfHoodLead($data): bool
    {
        $fields = ['title', 'first_name', 'last_name', 'phone', 'email'];
        foreach ($fields as $field) {
            if (is_null(data_get($data, $field))) {
                return false;
            }
        }
        return true;
    }

    private function checkIfNotHoodLead($data): bool
    {
        $fields = array_merge(self::MANDATORY_APP_FIELDS_NOT_HOOD_AI, [ 'identification.type', 'identification.card_number']);

        foreach ($fields as $field) {
            if (is_null(data_get($data, $field))){
                return false;
            }
        }
        return true;
    }

    private function setTriage(int $id, bool $res): void
    {
        ConnectionApplication::query()->where('id', $id)->update(['is_triage' => !$res]);
    }
}
