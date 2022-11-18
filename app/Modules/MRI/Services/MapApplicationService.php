<?php

namespace MRI\Services;

use App\Models\MriAgent;
use App\Models\MriOffice;
use App\Models\MriApplication;
use App\Models\MriProperty;

use App\Models\User;
use App\Models\AgentProfile;
use App\Models\ConnectionApplication;

use App\Services\NotifyBadAgentMailService;

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
    
    public function run ()
    {
        $mriApplications = MriApplication::doesntHave('connectionApplication')->get();

        $createdApplications = [];

        foreach ($mriApplications as $mriApp) {
            try {
                $mapApp = $this->mapNewApplication($mriApp);
                $conApp = ConnectionApplication::create($mapApp);
                $firstMriAgent = $conApp->createdBy?->user?->email;
                if (empty($firstMriAgent)){
                    $firstMriAgent = $conApp->mriApplication?->mriProperty?->mriAgents()?->first()?->email_address; 
                }
                NotifyBadAgentMailService::check(
                    $conApp, 
                    'MRI', 
                    $conApp->agency->name ?? '', 
                    $conApp->office->name ?? '', 
                    $firstMriAgent ?? ''
                );
                $createdApplications[] = $conApp;
                if (!empty($mriApp->authorized_first_name)) {
                    $authorizedPerson = $this->mapNewAuthorizedPerson($conApp->id, $mriApp);
                    $conApp->authorizedPerson()->create($authorizedPerson);
                }
    
            } catch (\Exception $e) {
                $this->exceptionHandler->addException($e);
            }
        }

        if (count($createdApplications) > 0) {
            dump(sprintf('Created %s applications from MRI', strval(count($createdApplications))));
        }

        if ($this->exceptionHandler->hasExceptions()){
            $this->exceptionHandler->run();
        }
    }

    /**
     * @param MriApplication
     * 
     * @return array
     */
    private function mapNewApplication (MriApplication $mriApp) 
    {
        $mriOffice = $mriApp->mriOffice;
        $mriProperty = $mriApp->mriProperty;
        $firstMriAgent = $mriProperty->mriAgents()->whereNotNull('agent_profile_id')->first();
        $newConnectionApp = [];

        $newConnectionApp['source'] = ConnectionApplication::SOURCE_MRI;
        $newConnectionApp['status'] = ConnectionApplication::STATUS_UNASSIGNED;
        $newConnectionApp['mri_application_id'] = $mriApp->id;
        $newConnectionApp['tenancy_type'] = ConnectionApplication::TENANCY_TYPE_RENTER;

        if (in_array($mriApp->title, ConnectionApplication::AVAILABLE_USER_TITLES)) $newConnectionApp['title'] = $mriApp->title; 
        $newConnectionApp['office_id'] = $mriOffice->office_id; //
        $newConnectionApp['agency_id'] = $mriOffice->office->agency_id; //
        if($firstMriAgent) $newConnectionApp['created_by'] = $firstMriAgent->agent_profile_id ?? null;
        $newConnectionApp['first_name'] = $mriApp->first_name;
        $newConnectionApp['last_name'] = $mriApp->last_name;
        $newConnectionApp['email'] = $mriApp->email_address;
        $newConnectionApp['phone'] = $mriApp->mobile_phone_number ?? null;
        $newConnectionApp['homephone'] = $mriApp->home_number ?? null;
        $newConnectionApp['moving_date'] = $mriApp->lease_start_date ?? null;
        $newConnectionApp['connection_end_date'] = $mriApp->lease_end_date ?? null;
        $newConnectionApp['is_email_marketing'] = $mriApp->is_marketing;
        $newConnectionApp['unit_number'] = $mriProperty->unit ?? null;
        $newConnectionApp['street_name'] = $mriProperty->address_line_1;
        $newConnectionApp['street_number'] = $mriProperty->street_number;
        $newConnectionApp['city'] = $mriProperty->suburb;
        $newConnectionApp['postcode'] = $mriProperty->post_code;
        $newConnectionApp['state'] = $mriProperty->state;
        $newConnectionApp['country'] = $mriProperty->country;
        $newConnectionApp['street_address'] =  sprintf('%s %s', $newConnectionApp['street_number'], $newConnectionApp['street_name']);
        if (!empty($newConnectionApp['unit_number'])) 
            $newConnectionApp['street_address'] = $newConnectionApp['unit_number'] . ' / ' . $newConnectionApp['street_address'];
        $newConnectionApp['address_text'] = $newConnectionApp['street_address'] . ', ' . $newConnectionApp['city'] . ' ' . $newConnectionApp['state']  . ' ' . $newConnectionApp['postcode'];
        if (!empty($mriProperty->management_type))
            $newConnectionApp['property_type'] = ConnectionApplication::PROPERTY_TYPE_MAPPING[strtolower($mriProperty->management_type)];

        return $newConnectionApp;
    }

    private function mapNewAuthorizedPerson(int $connection_application_id, MriApplication $mriApp)
    {
        $newAuthorizedPerson = [];
        $newAuthorizedPerson['title'] = $mriApp->authorized_title;
        $newAuthorizedPerson['first_name'] = $mriApp->authorized_first_name;
        $newAuthorizedPerson['last_name'] = $mriApp->authorized_last_name;
        $newAuthorizedPerson['email'] = $mriApp->authorized_email_address;
        $newAuthorizedPerson['phone'] = $mriApp->mobile_phone_number ?? ($mriApp->home_number ?? null);
        $newAuthorizedPerson['connection_application_id'] = $connection_application_id;

        return $newAuthorizedPerson;
    }
}