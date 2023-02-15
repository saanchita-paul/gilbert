<?php

namespace MRI\Services;

use App\Models\MriApplication;
use App\Models\ConnectionApplication;
use App\Models\MriProperty;
use App\Services\NotifyBadAgentMailService;
use App\Models\ConnectionService;
use App\Models\MriAgent;
// use MRI\Services\NotifyMissingDetailsService;
use App\Models\Office;
use App\Events\Agency\CreateApplicationEvent;

class MapApplicationService
{
    /**
     * @var Office
     */
    public Office $default_office;

    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    public function __construct()
    {
        $this->exceptionHandler = new HandleExceptionService(self::class);
        $this->default_office = Office::where('name', 'MRI Hood Office')->first();
    }

    public function run()
    {
        $mriApplications = MriApplication::doesntHave('connectionApplication')
                            ->where('is_deleted', false)
                            ->where('is_archived', false)
                            ->get();

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
                CreateApplicationEvent::dispatch($conApp->id);
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
    }

    /**
     * @param MriApplication
     * @return array
     */
    private function mapNewApplication(MriApplication $mriApp)
    {
        $mriOffice = $mriApp->mriOffice;
        $mriProperty = $mriApp->mriProperty;
        $firstMriAgent = $this->getFirstPropertyManager($mriProperty);
        $newConnectionApp = [];

        $newConnectionApp['source'] = ConnectionApplication::SOURCE_MRI;
        $newConnectionApp['status'] = ConnectionApplication::STATUS_UNASSIGNED;
        $newConnectionApp['mri_application_id'] = $mriApp->id;
        $newConnectionApp['tenancy_type'] = ConnectionApplication::TENANCY_TYPE_RENTER;

        if (in_array($mriApp->title, ConnectionApplication::AVAILABLE_USER_TITLES)) {
            $newConnectionApp['title'] = $mriApp->title;
        }
        $newConnectionApp['office_id'] = $mriOffice->office_id ?? $this->default_office->id;
        $newConnectionApp['agency_id'] = $mriOffice->office->agency_id ?? $this->default_office->agency_id;
        if ($firstMriAgent) {
            $newConnectionApp['created_by'] = $firstMriAgent->agent_profile_id ?? null;
        }
        $newConnectionApp['first_name'] = $mriApp->first_name;
        $newConnectionApp['last_name'] = $mriApp->last_name;
        $newConnectionApp['email'] = $mriApp->email_address;
        $newConnectionApp['phone'] = $mriApp->mobile_phone_number ?? null;
        $newConnectionApp['homephone'] = $mriApp->home_number ?? null;
        $newConnectionApp['phone_type'] = ConnectionApplication::PHONE_TYPE_MOBILE;
        if (($mriApp->preferred_phone_number ?? '') == $newConnectionApp['homephone']) {
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
        $newConnectionApp['is_billing_same'] = true;
        if (!empty($mriProperty->management_type)) {
            $managementType = strtolower($mriProperty->management_type);
            $newConnectionApp['property_type'] = ConnectionApplication::PROPERTY_TYPE_MAPPING[$managementType];
        }

        return $newConnectionApp;
    }

    // private function mapNewAuthorizedPerson(int $connection_application_id, MriApplication $mriApp)
    // {
    //     $newAuthorizedPerson = [];
    //     $newAuthorizedPerson['title'] = $mriApp->authorized_title;
    //     $newAuthorizedPerson['first_name'] = $mriApp->authorized_first_name;
    //     $newAuthorizedPerson['last_name'] = $mriApp->authorized_last_name;
    //     $newAuthorizedPerson['email'] = $mriApp->authorized_email_address;
    //     if (!empty($mriApp->authorized_preferred_phone_number)) {
    //         $newAuthorizedPerson['phone'] = $mriApp->authorized_preferred_phone_number;
    //     } else {
    //         $newAuthorizedPerson['phone'] = $mriApp->authorized_mobile_phone_number ?? null;
    //     }
    //     $newAuthorizedPerson['connection_application_id'] = $connection_application_id;

    //     return $newAuthorizedPerson;
    // }

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
        $conApp = ConnectionApplication::create($mapApp);

        foreach (ConnectionService::SERVICE_TYPES as $serviceType) {
            $serviceDetail = $this->mapNewConnectionService($conApp->id, $serviceType);
            $conApp->connectionServices()->create($serviceDetail);
        }
        return $conApp;
    }

    private function getFirstPropertyManager(MriProperty $mriProperty)
    {
        $firstPropertyManager = false;
        $mriAgents = $mriProperty->mriAgents()->whereNotNull('agent_profile_id')->get();
        foreach ($mriAgents as $mriAgent) {
            if ($firstPropertyManager) {
                break;
            }
            $rolesList = $mriAgent->roles_list ?? [];
            if (in_array(MriAgent::ROLE_PROPERTY_MANAGER, $rolesList)) {
                $firstPropertyManager = $mriAgent;
            }
        }

        return $firstPropertyManager;
    }
}
