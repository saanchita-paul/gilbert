<?php

namespace MRI\Services;

use App\Models\MriApplication;
use App\Models\ConnectionApplication;
use App\Services\NotifyBadAgentMailService;
use App\Models\ConnectionService;
use MRI\Services\NotifyMissingDetailsService;

class MapApplicationService
{
    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    public function __construct()
    {
        $this->exceptionHandler = new HandleExceptionService(self::class);
    }

    public function run()
    {
        $missingService = new NotifyMissingDetailsService();
        $mriApplications = MriApplication::doesntHave('connectionApplication')->get();

        $createdApplications = [];

        foreach ($mriApplications as $mriApp) {
            try {
                $conApp = $this->createConnectionApplication($mriApp);
                $firstMriAgent = $conApp->createdBy?->user?->email;
                if (empty($firstMriAgent)) {
                    $firstMriAgent = $conApp->mriApplication?->mriProperty?->mriAgents()?->first()?->email_address;
                }
                NotifyBadAgentMailService::check(
                    $conApp,
                    'MRI',
                    $conApp->agency->name ?? '',
                    $conApp->office->name ?? '',
                    $firstMriAgent ?? ''
                );
                $missingService->check($conApp);
                $createdApplications[] = $conApp;
            } catch (\Exception $e) {
                $this->exceptionHandler->addException($e);
            }
        }

        if (count($createdApplications) > 0) {
            info(sprintf('Created %s applications from MRI', strval(count($createdApplications))));
        }

        if ($this->exceptionHandler->hasExceptions()) {
            $this->exceptionHandler->run();
        }

        $missingService->notifyIfAny();
    }

    /**
     * @param MriApplication
     * @return array
     */
    private function mapNewApplication(MriApplication $mriApp)
    {
        $mriOffice = $mriApp->mriOffice;
        $mriProperty = $mriApp->mriProperty;
        $firstMriAgent = $mriProperty->mriAgents()->whereNotNull('agent_profile_id')->first();
        $newConnectionApp = [];

        $newConnectionApp['source'] = ConnectionApplication::SOURCE_MRI;
        $newConnectionApp['status'] = ConnectionApplication::STATUS_UNASSIGNED;
        $newConnectionApp['mri_application_id'] = $mriApp->id;
        $newConnectionApp['tenancy_type'] = ConnectionApplication::TENANCY_TYPE_RENTER;

        if (in_array($mriApp->title, ConnectionApplication::AVAILABLE_USER_TITLES)) {
            $newConnectionApp['title'] = $mriApp->title;
        }
        $newConnectionApp['office_id'] = $mriOffice->office_id; //
        $newConnectionApp['agency_id'] = $mriOffice->office->agency_id; //
        if ($firstMriAgent) {
            $newConnectionApp['created_by'] = $firstMriAgent->agent_profile_id ?? null;
        }
        $newConnectionApp['first_name'] = $mriApp->first_name;
        $newConnectionApp['last_name'] = $mriApp->last_name;
        $newConnectionApp['email'] = $mriApp->email_address;
        $newConnectionApp['phone'] = $mriApp->mobile_phone_number ?? null;
        $newConnectionApp['homephone'] = $mriApp->home_number ?? null;
        $newConnectionApp['phone_type'] = ConnectionApplication::PHONE_TYPE_MOBILE;
        if ($mriApp->preferred_phone_number ?? '' == $newConnectionApp['homephone']) {
            $newConnectionApp['phone_type'] = ConnectionApplication::PHONE_TYPE_HOMEPHONE;
        }
        $newConnectionApp['moving_date'] = $mriApp->lease_start_date ?? null;
        $newConnectionApp['connection_end_date'] = $mriApp->lease_end_date ?? null;
        $newConnectionApp['is_email_marketing'] = $mriApp->is_marketing;
        $newConnectionApp['unit_number'] = $mriProperty->unit ?? null;
        $newConnectionApp['street_name'] = $mriProperty->address_line_1;
        $addressLine1 = explode(" ", $mriProperty->address_line_1, 2);
        $newConnectionApp['street_name_only'] = $addressLine1[0];
        $newConnectionApp['street_type'] = $addressLine1[1] ?? null;
        $newConnectionApp['street_number'] = $mriProperty->street_number;
        $newConnectionApp['city'] = $mriProperty->suburb;
        $newConnectionApp['postcode'] = $mriProperty->post_code;
        $newConnectionApp['state'] = $mriProperty->state;
        $newConnectionApp['country'] = $mriProperty->country;
        $newConnectionApp['street_address'] = sprintf('%s %s', $newConnectionApp['street_number'], $newConnectionApp['street_name']);
        if (!empty($newConnectionApp['unit_number'])) {
            $newConnectionApp['street_address'] = $newConnectionApp['unit_number'] . ' / ' . $newConnectionApp['street_address'];
        }
        $newConnectionApp['address_text'] = $newConnectionApp['street_address'] . ', ' . $newConnectionApp['city'] . ' ' . $newConnectionApp['state']  . ' ' . $newConnectionApp['postcode'];
        if (!empty($mriProperty->management_type)) {
            $managementType = strtolower($mriProperty->management_type);
            $newConnectionApp['property_type'] = ConnectionApplication::PROPERTY_TYPE_MAPPING[$managementType];
        }

        return $newConnectionApp;
    }

    private function mapNewAuthorizedPerson(int $connection_application_id, MriApplication $mriApp)
    {
        $newAuthorizedPerson = [];
        $newAuthorizedPerson['title'] = $mriApp->authorized_title;
        $newAuthorizedPerson['first_name'] = $mriApp->authorized_first_name;
        $newAuthorizedPerson['last_name'] = $mriApp->authorized_last_name;
        $newAuthorizedPerson['email'] = $mriApp->authorized_email_address;
        if (!empty($mriApp->authorized_preferred_phone_number)) {
            $newAuthorizedPerson['phone'] = $mriApp->authorized_preferred_phone_number;
        } else {
            $newAuthorizedPerson['phone'] = $mriApp->authorized_mobile_phone_number ?? null;
        }
        $newAuthorizedPerson['connection_application_id'] = $connection_application_id;

        return $newAuthorizedPerson;
    }

    private function mapNewConnectionService(int $connection_application_id, string $serviceType)
    {
        return [
            'connection_application_id' => $connection_application_id,
            'service_type' => $serviceType,
            'status' => ConnectionService::STATUS_EA_PROCESSINF,
        ];
    }

    private function createConnectionApplication($mriApp)
    {
        $mapApp = $this->mapNewApplication($mriApp);
        list($noteAppData, $noteIdentificationData) = $this->mapApplicationNoteFields($mriApp);
        if (!empty($noteAppData)) {
            $mapApp = array_merge($mapApp, $noteAppData);
            $mriApp->has_process_note = true;
            $mriApp->save();
        }
        $conApp = ConnectionApplication::create($mapApp);
        if (!empty($mriApp->authorized_first_name)) {
            $authorizedPerson = $this->mapNewAuthorizedPerson($conApp->id, $mriApp);
            $conApp->authorizedPerson()->create($authorizedPerson);
        }
        if (!empty($noteIdentificationData)) {
            $conApp->identification()->create($noteIdentificationData);
        }
        foreach (ConnectionService::SERVICE_TYPES as $serviceType) {
            $serviceDetail = $this->mapNewConnectionService($conApp->id, $serviceType);
            $conApp->connectionServices()->create($serviceDetail);
        }
        return $conApp;
    }

    private function mapApplicationNoteFields($mriApp)
    {
        $conAppData = [];
        $identificationData = [];
        $mriNoteData = $mriApp->mriNoteData;

        if ($mriNoteData) {
            $descData = explode("\n", $mriNoteData->description);
            if ($descData[0] === 'HOOD_DATA') {
                array_shift($descData);
                foreach ($descData as $line) {
                    $field = explode(":", $line);
                    $key = strtoupper(trim($field[0] ?? ''));
                    $val = trim($field[1] ?? '');
                    if (array_key_exists($key, MriApplication::CONNECTION_APPLICATION_FIELDS) && !empty($val)) {
                        $columnName = MriApplication::CONNECTION_APPLICATION_FIELDS[$key];
                        $conAppData[$columnName] = $val;
                    }
                    if (array_key_exists($key, MriApplication::IDENTIFICATION_FIELDS) && !empty($val)) {
                        $columnName = MriApplication::IDENTIFICATION_FIELDS[$key];
                        $identificationData[$columnName] = $val;
                    }
                    if (array_key_exists($key, MriApplication::IDENTIFICATION_TYPE) && !empty($val)) {
                        $columnName = 'type';
                        $columnVal = MriApplication::IDENTIFICATION_TYPE[$key];
                        $identificationData[$columnName] = $columnVal;
                    }
                }
            }
        }
        $return = [$conAppData, $identificationData];
        return $return;
    }
}
