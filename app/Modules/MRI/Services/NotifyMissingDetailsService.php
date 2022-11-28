<?php

namespace MRI\Services;

use App\Models\ConnectionApplication;
use MRI\Mail\NotifyMissingDetailsMail;
use Illuminate\Support\Facades\Mail;

class NotifyMissingDetailsService
{
    /**
     * List of required fields in Connection Application Table.
     * Ensure fields with || to not be the first item
     * @param array
     */
    public const CONNECTION_APPLICATION_REQUIRED_FIELDS = [
        'title',
        'first_name',
        'last_name',
        'dob',
        'email',
        'phone||homephone',
        'moving_date',
        'tenancy_type',
    ];

    /**
     * List of required fields in Identification Table.
     * Ensure fields with || to not be the first item
     * @param array
     */
    public const IDENTIFICATION_REQUIRED_FIELDS = [
        'type',
        'card_number',
        'expire_date'
    ];

    private array $incompleteApps;

    public function __construct()
    {
    }

    public function check(ConnectionApplication $conApp)
    {
        $this->checkConnectionApplicationFields($conApp);
        $this->checkIdentificationFields($conApp);

        return $this->incompleteApps;
    }

    public function notifyIfAny()
    {
        if (count($this->incompleteApps) > 0) {
            $emails = explode(',', config('support_email.agent_not_found'));
            foreach ($emails as $recipient) {
                Mail::to($recipient)->queue(new NotifyMissingDetailsMail($this->incompleteApps));
            }
            return true;
        }

        return false;
    }

    private function checkConnectionApplicationFields(ConnectionApplication $conApp)
    {
        foreach (self::CONNECTION_APPLICATION_REQUIRED_FIELDS as $field) {
            $orFields = explode("||", $field);
            if (count($orFields) > 1) {
                $missingOrFields = [];
                foreach ($orFields as $orField) {
                    if (empty($conApp->{$orField})) {
                        $missingOrFields[] = 'application ' . str_replace("_", ' ', $orField);
                    }
                }
                if (count($missingOrFields) === count($orFields)) {
                    $update = array_merge($this->incompleteApps[$conApp->id], $missingOrFields);
                    $this->incompleteApps[$conApp->id] = $update;
                }
            } else {
                if (empty($conApp->{$field})) {
                    $this->incompleteApps[$conApp->id][] = 'application ' . str_replace("_", ' ', $field);
                }
            }
        }
    }

    private function checkIdentificationFields(ConnectionApplication $conApp)
    {
        $ident = $conApp->identification;
        foreach (self::IDENTIFICATION_REQUIRED_FIELDS as $field) {
            $orFields = explode("||", $field);
            if (count($orFields) > 1) {
                $missingOrFields = [];
                foreach ($orFields as $orField) {
                    if (empty($ident->{$orField} ?? '')) {
                        $missingOrFields[] = 'identification ' . str_replace("_", ' ', $orField);
                    }
                }
                if (count($missingOrFields) === count($orFields)) {
                    array_merge($this->incompleteApps[$conApp->id], $missingOrFields);
                }
            } else {
                if (empty($ident->{$field}) ?? '') {
                    $missingField = 'identification ' . str_replace("_", ' ', $field);
                    $this->incompleteApps[$conApp->id][] = $missingField;
                }
            }
        }
    }
}
