<?php

namespace App\Services\Agency;

use App\Jobs\UpdateHubspotContactJob;
use App\Models\AppCloseReason;
use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;
use App\Models\HoodProfile;
use App\Models\Identification;
use App\Models\Office;
use App\Models\User;
use App\Services\RolePermission;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use TSA\Services\TsaSendAppliationService;
use Illuminate\Support\Str;


class ApplicationService
{
    /**
     * @param array $application
     * @param User $user
     * @return ConnectionApplication
     */
    public function createApplication(array $application, User $user)
    {

        $agentProfile = $user->profile;
        $application['office_id'] = $agentProfile->office_id;
        $application['agency_id'] = $agentProfile->agency_id;
        $application['created_by'] = $agentProfile->id;
        $application['status'] = ConnectionApplication::STATUS_UNASSIGNED;
        $authizedPerson = $application['authorized_person'];

        if($application['is_billing_same'] == 0 || $application['is_billing_same'] == null ) {

            $application['billing_unit_number'] = $application['billing_unit_number'];
            $application['billing_street_number'] = $application['billing_street_number'];
            $application['billing_street_name_only'] = $application['billing_street_name_only'];
            $application['billing_address_text'] = $application['billing_address_text'];
            $application['billing_street_address'] = $application['billing_street_address'];
            $application['billing_street_type'] = $application['billing_street_type'];
            $application['billing_city'] = $application['billing_city'];
            $application['billing_postcode'] = $application['billing_postcode'];
            $application['billing_state'] = $application['billing_state'];
            $application['billing_address_unit'] = $application['billing_unit_number'] ? $application['billing_unit_number'] : null;
        }
        else {
            $application['billing_unit_number'] = $application['unit_number'];
            $application['billing_street_number'] = $application['street_number'];
            $application['billing_street_name_only'] = $application['street_name_only'];
            $application['billing_address_text'] = $application['address_text'];
            $application['billing_street_address'] = $application['street_address'];
            $application['billing_street_type'] = $application['street_type'];
            $application['billing_city'] = $application['city'];
            $application['billing_postcode'] = $application['postcode'];
            $application['billing_state'] = $application['state'];
            $application['billing_address_unit'] = $application['unit_number'] ? $application['unit_number'] : null;
        }

        /** @var $newApplication ConnectionApplication */
        $newApplication = ConnectionApplication::create($application);
        $connectionService = [];


        $this->createApplicationSerive($newApplication->id, $application['service_interests']);

//        foreach ($application['service_interests'] as $service) {
//            $connectionService[] = ConnectionService::create(
//                ['service_type' => $service['service_type'],
//                    'connection_application_id' => $newApplication->id]
//            );
//        }

        if (!empty($authizedPerson)) {
            $authizedPerson['connection_application_id'] = $newApplication->id;
        }
        ConnectionApplicationSecondaryACC::create($authizedPerson);
        $this->createIdentification($application['identification'], $newApplication->id);

        return $newApplication;

    }


    public function getNotes($application_id)
    {
        return ApplicationNote::query()
            ->where('connection_application_id', '=', $application_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createNotes(array $note, User $user, $applicationId)
    {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $user->id;
        if ($note['title'] == null) {
            $note['title'] = 'Note by ' . $user->profile->first_name;
        }
        return ApplicationNote::create($note);
    }

    public function updateApplication(array $application, int $applicationId)
    {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->assigned_to = $application['agent_profile_id'];
        $existingApplication->status = ConnectionApplication::STATUS_SUBMITTED;
        $existingApplication->save();
    }

    public function updateAddress(array $address, int $applicationId)
    {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->address_text = $address['address_text'];
        $existingApplication->street_address = $address['street_address'];
        $existingApplication->street_name = $address['street_name'];
        $existingApplication->street_name_only = $address['street_name_only'];
        $existingApplication->unit_number = $address['unit_number'];
        $existingApplication->street_type = $address['street_type'];
        $existingApplication->street_number = $address['street_number'];
        // $existingApplication->street_number = empty($address['street_address']) ? null : $address['street_number'];
        // $existingApplication->unit_number = empty($address['street_address']) ? null : $address['unit_number'];
        $existingApplication->city = $address['city'];
        $existingApplication->is_renovation_on = $address['is_renovation_on'];
        $existingApplication->has_electricity = $address['has_electricity'];
        $existingApplication->inspection_time = $address['inspection_time'];
        $existingApplication->postcode = $address['postcode'];
        $existingApplication->state = $address['state'];
        $existingApplication->country = $address['country'];
        $existingApplication->mirn = $address['mirn'];
        $existingApplication->nmi = $address['nmi'];
        $existingApplication->is_billing_same = $address['is_billing_same'];



        if ($address['is_billing_same'] == 0 || $address['is_billing_same'] == null) {
            $existingApplication->billing_address_text = $address['billing_address_text'];
            $existingApplication->billing_state = $address['billing_state'];
            $existingApplication->billing_unit_number = $address['billing_unit_number'];
            $existingApplication->billing_street_type = $address['billing_street_type'];
            $existingApplication->billing_street_address = $address['billing_street_address'];
            // $existingApplication->billing_street_name = $address['billing_street_name'];
            $existingApplication->billing_street_name_only = $address['billing_street_name_only'];
            $existingApplication->billing_street_number = empty($address['billing_street_address']) ? null : $address['billing_street_number'];
            $existingApplication->billing_city = empty($address['billing_city']) ? null : $address['billing_city'];
            $existingApplication->billing_postcode = empty($address['billing_postcode']) ? null : $address['billing_postcode'];
            $existingApplication->billing_address_unit = $address['billing_unit_number'] ? $address['billing_unit_number'] : null;
        } else {
            $existingApplication->billing_address_text = $address['address_text'];
            $existingApplication->billing_state = $address['state'];
            $existingApplication->billing_unit_number = $address['unit_number'];
            $existingApplication->billing_street_type = $address['street_type'];
            $existingApplication->billing_street_address = $address['street_address'];
            // $existingApplication->billing_street_name = $address['street_name'];
            $existingApplication->billing_street_name_only = $address['street_name_only'];
            $existingApplication->billing_street_number = empty($address['street_address']) ? null : $address['street_number'];
            $existingApplication->billing_city = empty($address['city']) ? null : $address['city'];
            $existingApplication->billing_postcode = empty($address['postcode']) ? null : $address['postcode'];
            $existingApplication->billing_address_unit = $address['unit_number'] ? $address['unit_number'] : null;

        };
        $existingApplication->save();

        return $existingApplication;
    }

    public function assignUser(string $agentId, int $applicationId)
    {
        ConnectionApplication::query()
            ->where('id', $applicationId)
            ->update(['assigned_to' => $agentId, 'status' => ConnectionApplication::STATUS_ASSIGNED]);

        if (in_array(HoodProfile::find($agentId)->user->roles->first()?->name,
            [RolePermission::ROLE_EXTERNAL_HOOD_TEAM_LEAD])) {
            $tsaService = new TsaSendAppliationService($applicationId);
            $tsaService->sendApplication();
            $tsa_lead_id = $tsaService->getTsaLeadId();
            $existingApplication = ConnectionApplication::find($applicationId);
            $existingApplication->tsa_lead_id = $tsa_lead_id;
            $existingApplication->save();
        }
        return $this->findApplications($applicationId);
    }


    public function findApplications(int $id)
    {
        return ConnectionApplication::query()
            ->where('id', $id)
            ->with('connectionServices', 'identification')
            ->first();
    }

    public function updateConnectionService(array $serviceList, $id)
    {
        #deleting not selected service
        ConnectionService::query()->where('connection_application_id', '=', $id)
            ->whereNotIn('service_type', $serviceList)
            ->delete();


        $conServices =  ConnectionService::query()->where('connection_application_id', '=', $id)
            ->get();
        #saving newly selected service
        $data = [];
        $currentService = $conServices->pluck('service_type')->toArray();

        $planProvider = $this->getEnergyPlanAndProvider($conServices);
        foreach ($serviceList as $item) {
            if (!in_array($item, $currentService)) {
                $serviceData = [
                    'service_type' => $item,
                    'connection_application_id' => $id,
                    'status' => ConnectionService::STATUS_EA_PROCESSINF
                ];
                if (in_array($item, [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS ])) {
                    $serviceData['provider_name'] = $planProvider['provider_name'];
                    $serviceData['plan_type'] = $planProvider['plan_type'];
                }

                $data[] = $serviceData;
            }
        }

        ConnectionService::insert($data);
    }

    #[ArrayShape(['plan_type' => "null|string", 'provider_name' => "null|string"])]
    private function getEnergyPlanAndProvider($conServices): array
    {
        $provider = null;
        $plan = null;
        foreach ($conServices as $service) {
            if (in_array($service->service_type, [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS ])) {
                $provider = $service->provider_name ?? null;
                $plan = $service->plan_type ?? null;
            }
        }

        return ['plan_type' => $plan, 'provider_name' => $provider];
    }

    public function createIdentification($identificationData, $id)
    {


        \Log::info('identification data_' . $id, $identificationData);
        $identification = Identification::where('connection_application_id', $id)->first();
        if (isset($identificationData['medicare_expire_date'])) {
            unset($identificationData['medicare_expire_date']);
        }

        if ($identification) {
            $identification->update($identificationData);

        } else {
            $identificationData['connection_application_id'] = $id;
            return Identification::create($identificationData);
        }


    }


    public function submit(array $applications, $id)
    {

        $existLead = ConnectionApplication::query()->where('id', $id)->firstOrFail();

        $lead = $applications['lead'];
        $vendorId = $this->calculateVendorId($existLead);
        $lead = array_merge($lead, [
            'plan_type' => null,
            'submitted_by' => auth()->id(),
            'vendor_id' => $vendorId
        ]);


        $existLead->update($lead);

        $this->createIdentification($lead['identification'], $id);

//      $this->updateConnectionService($lead['service_interests'], $id);

        $this->checkAndAddWaterService($applications, $existLead);

        $submitType = data_get($applications, 'lead.submit_type');

        $this->setSubmittedAtByServiceType($id, $submitType, $lead['service_interests']);

//        $noteService = new SubmittedLeadNote($existLead);
//        $noteService->addSubmittedNote();
        return $existLead;
    }


    /** set submitted_at to connection_services table
     *
     * @param int $applicationId
     * @param string $type
     * @param array $service_interests
     * @return bool $status
     */
    public function setSubmittedAtByServiceType(int $applicationId, string $type, array $service_interests = []): bool
    {
        try {
            $services = match ($type) {
                'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
                'power' => [ConnectionService::TYPE_ELECTRICITY],
                'gas' => [ConnectionService::TYPE_GAS],
                'water' => [ConnectionService::TYPE_WATER],
            };
            $query = ConnectionService::where('connection_application_id', $applicationId);
            $query = $query->whereIn('service_type', $services);
            $query->update(["submitted_at" => now()]);

            return true;
        } catch (\Exception $exception) {
            \Log::error("**Service submitted at, service not found**",
                ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]);
            return false;
        }
    }

    private function checkAndAddWaterService(array $applications, $application)
    {
        // if( array_search("water",$applications['lead']['service_interests'])){
        //     info("water found");
        // };
        if ($applications['lead']['submit_type'] == 'water') {

            $waterService = ConnectionService::where("connection_application_id", $application->id)
                ->where("service_type", "water")->first();
            if (!$waterService) {
                $application->connectionServices()->create(['service_type' => 'water', 'status' => ConnectionService::STATUS_EA_PROCESSINF]);
            } else {
                info('water found inside');
                \Log::info($waterService);
            }

        };


    }


    public function updateEscalateApplication(array $application, int $applicationId, User $user)
    {

        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->reason = $application['reason'];
//        $existingApplication->status = ConnectionApplication::STATUS_MAPPING[$application['status']];
        $existingApplication->status = ConnectionApplication::STATUS_ESCALATED;
        $existingApplication->save();

        UpdateHubspotContactJob::dispatch($applicationId);

        $allicationNoteService = new ApplicationNoteService($user);
        $eacalateNote = [];
        $eacalateNote['text'] = $application['reason'];
        $eacalateNote['type'] = 'Escalated';

        $allicationNoteService->createNotes($eacalateNote, $applicationId);
        return $existingApplication;
    }

    /**
     *
     * @param array $application
     * @param int $applicationId
     * @param User $user
     * @return ConnectionApplication $existingApplication
     */
    public function closeApplicationWithReason(array $application, int $applicationId, User $user)
    {

        try {
            $existingApplication = ConnectionApplication::find($applicationId);
            $existingApplication->app_close_reason_id = $application['app_close_reason_id'];
            $existingApplication->closing_reason = $application['closing_reason'] ?? null;
            $existingApplication->status = ConnectionApplication::STATUS_CLOSED;
            $existingApplication->closed_at = now();
            $existingApplication->closed_by = $user->profile->id;
            $existingApplication->save();

            UpdateHubspotContactJob::dispatch($applicationId);

            // get dropdown reason id text
            $applicationReasonIdText = AppCloseReason::select('value')->where('id', $application['app_close_reason_id'])->first();

            $allicationNoteService = new ApplicationNoteService($user);
            $closingeNote = [];
            $closingeNote['text'] = $application['closing_reason'] ?? $applicationReasonIdText?->value;
            $closingeNote['type'] = 'close_connection';

            $allicationNoteService->createNotes($closingeNote, $applicationId);


            return $existingApplication;
        } catch (\Exception $exception) {
            \Log::error("**CloseApplication**",
                ["msg" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]);
        }
    }

    public function updateSoleField(array $application, $id)
    {
        $existLead = ConnectionApplication::findOrFail($id);
        $isIdentification = $application['identification'];
        $isService = $application['isService'];

        unset($application['identification']);
        unset($application['isService']);

        if (isset($application['email_manually_verified_by'])) {
            if ($application['email_manually_verified_by'] === true) {
                $application['email_manually_verified_by'] =  auth()->user()->profile_id;
            } else {
                $application['email_manually_verified_by'] =  null;
            }
        }

        if ($isIdentification) {
            $this->createIdentification($application, $id);
        } else if ($isService) {
            $this->updateConnectionService($application['service_types'], $id);
        } else {

            if (isset($application['plan_type']) && isset($application['plan_type']['key'])) {
                $application['plan_type'] = ConnectionApplication::PLAN_TYPE_MAPPER[$application['plan_type']['key']];
            }

            $existLead->update($application);
        }

        return $existLead->refresh();
    }

    public function getAuthrisedInfo($id)
    {
        return ConnectionApplicationSecondaryACC::query()
            ->where('connection_application_id', '=', $id)
            ->first();
    }

    public function updateAuthrisedInfo($data)
    {
        $id = $data['id'];
        if (!empty($id)) {
            $authorizedPerson = ConnectionApplicationSecondaryACC::find($id);
            $authorizedPerson->update($data);
            return $authorizedPerson->refresh();
        } else {
            $authorizedPerson = ConnectionApplicationSecondaryACC::create($data);
            return $authorizedPerson;
        }

    }

    private function calculateVendorId($lead)
    {
        /** @var Office $office */
        $office = $lead->office;
        return $office->getVendorCode() . '_CRM' . str_pad($lead->id, 10, "0", STR_PAD_LEFT);
    }

    public function closeApplication($id)
    {
        $connectionApplication = ConnectionApplication::find($id);
        $connectionApplication->update(['status' => 8]);
        return $connectionApplication->refresh();
    }

    public function createApplicationSerive($id, $services): void
    {
        foreach ($services as $service) {
            $connectionService[] = ConnectionService::create(
                [
                    'service_type' => $service['service_type'],
                    'connection_application_id' => $id,
                    'status' => ConnectionService::WATER_STATUS_IN_PROGRESS
                ]
            );
        }
    }

    public function updateService(array $data)
    {

        try {
            return ConnectionService::updateOrCreate(
                ['id' => $data['id'] ?? null],
                $data
            );
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function providers(array $data, $applicationId)
    {
        $services = [];
        $provider_service_type = $data['service_area'] ?? '';

        $services = match ($provider_service_type) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS],
            'internet' => [ConnectionService::TYPE_INTERNET]
        };

        foreach ($services as $service) {

            $plan = $data['plan_type'];
            if($data['provider_name'] === 'origin' && $data['plan_type'] !== null) {
                $plan = match ($service) {
                    ConnectionService::TYPE_ELECTRICITY => ConnectionService::ORIGIN_HOME_ASSIST_PLAN,
                    ConnectionService::TYPE_GAS => ConnectionService::ORIGIN_ADVANTAGE_VARIABLE_PLAN,
                };
            }

            $connectionService = ConnectionService::where('connection_application_id', $applicationId)
                ->where('service_type', $service)
                ->first();
            if ($connectionService) {
                $connectionService->provider_name = $data['provider_name'];
                $connectionService->plan_type = $plan;
                $connectionService->save();
            } else {
                ConnectionService::create(
                    [
                        'service_type' => $service,
                        'connection_application_id' => $applicationId,
                        'status' => ConnectionService::STATUS_EA_PROCESSINF,
                        'provider_name' => $data['provider_name'],
                        'plan_type' => $plan,
                    ]
                );
            }
        }
    }

    public function getNotSubmittedServices($id, $submitType) : array
    {
        $providers = [ConnectionService::PROVIDER_EA, ConnectionService::PROVIDER_ORIGIN];

        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS],
            default => []
        };

        $notSubmitted = [];

        foreach($providers as $provider){
            $notSubmitted[$provider] = ConnectionService::query()->where('connection_application_id', $id)
            ->where('provider_name', $provider)
            ->whereNull('lead_reference')
            ->whereIn('service_type', $services)
            ->pluck('id')->toArray();
        }

        return $notSubmitted;
    }

    public function getNotSubmittedEaService($id, $submitType): array
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS],
            default => []
        };

        return ConnectionService::query()->where('connection_application_id', $id)
            ->where('provider_name', ConnectionService::PROVIDER_EA)
            ->whereNull('lead_reference')
            ->whereIn('service_type', $services)
            ->pluck('id')->toArray();
    }

    public function getAssignedHoodUser($id)
    {
        $existingApplication = ConnectionApplication::find($id);
        return $existingApplication->assigned_to;
    }

    /** generate the uuid and save it to ConnectionApplication Table
     *
     * @param int $applicationId
     * @return string $sumoUuid
     */
    public function generateSumoUuid($applicationId)
    {
        $sumoUuid = Str::uuid()->toString();

        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->sumo_uuid = $sumoUuid;
        $existingApplication->save();

        return $sumoUuid;
    }


    public function clearConcession($id)
    {
        $existLead = ConnectionApplication::findOrFail($id);

        $existLead->update([
            'concession_card_type' => null,
            'concession_card_number' => null,
            'concession_start_date' => null,
            'concession_end_date' => null
        ]);

        return $existLead->refresh();
    }



    public function updateEmailField(array $application, $id)
    {
        $existLead = ConnectionApplication::findOrFail($id);
        ConnectionApplication::where('id' , $existLead->id)
            ->update([
                'email_manually_verified_by' => null,
            ]);
        return $existLead->refresh();
    }


    public function isEmailManuallyVerified($applicationId)
    {
        $existingApplication = ConnectionApplication::findOrFail($applicationId);
        $email_manually_verified_by = $existingApplication->email_manually_verified_by;

        return $email_manually_verified_by;
    }

}
