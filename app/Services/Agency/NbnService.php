<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\GoodtelPlan;
use App\Models\InternetServiceInfo;

class NbnService
{
    public function updateInternetServiceInfo(array $data, int $applicationId)
    {
        $existingApplication = ConnectionApplication::find($applicationId);
        $connectionService = $existingApplication->connectionServices()->where('service_type', 'internet')->first();

        $existingApplication->internetServiceInfo()->updateOrCreate([
            'connection_application_id' => $applicationId,
        ], [
            'connection_service_id' => $connectionService->id ?? null,
            'is_shipping_same' => $data['address']['is_same'],
            'unit_number' => $data['address']['unit_number'],
            'street_number' => $data['address']['street_number'],
            'street_name_only' => $data['address']['street_name_only'],
            'address_text' => $data['address']['address_text'],
            'street_address' => $data['address']['street_address'],
            'street_type' => $data['address']['street_type'],
            'city' => $data['address']['city'],
            'postcode' => $data['address']['postcode'],
            'state' => $data['address']['state'],
            'is_need_home_phone' => $data['is_need_home_phone'],
            'is_back_to_base' => $data['is_back_to_base'],
            'is_security_alarm' => $data['is_security_alarm'],
            'is_existing_landline' => $data['is_existing_landline'],
            'home_phone_number' => $data['home_phone_number'],
            'home_phone_provider' => $data['home_phone_provider'],
            'home_phone_plan' => $data['home_phone_plan'],
            'current_provider' => $data['current_provider'],
            'account_number' => $data['account_number'],
            'otp' => $data['otp'],
            'modem_type' => $data['modem_type'],
            'charity' => $data['charity']
        ]);

        return $existingApplication;
    }

    public function updateNbnProvider(array $data, $applicationId)
    {
        $connectionApplication = ConnectionApplication::find($applicationId);
        $connectionService = $connectionApplication->connectionServices()
            ->where('service_type', $data['service_type'])
            ->first();

        if ($connectionService) {
            $connectionService->update([
                'provider_name' => $data['provider_name'],
                'plan_type' => $data['plan_type'],
            ]);
        } else {
            $connectionService = ConnectionService::create([
                'service_type' => $data['service_type'],
                'connection_application_id' => $applicationId,
                'status' => ConnectionService::STATUS_EA_PROCESSINF,
                'provider_name' => $data['provider_name'],
                'plan_type' => $data['plan_type'],
            ]);
        }

        $internetServiceInfo = $connectionApplication->internetServiceInfo;
        $plan = GoodtelPlan::where('name', $data['plan_type'])->first();

        if ($internetServiceInfo) {
            $internetServiceInfo->update([
                'connection_service_id' => $connectionService->id,
                'goodtel_plan_id' => $plan->id,
            ]);
        } else {
            InternetServiceInfo::create([
                'connection_application_id' => $applicationId,
                'connection_service_id' => $connectionService->id,
            ]);
        }

        return $connectionApplication;
    }

    public function submitNBN(array $data, $applicationId)
    {
        $connectionApplication = ConnectionApplication::find($applicationId);
        $connectionService = $connectionApplication->connectionServices()
            ->where('service_type', 'internet')
            ->first();
        $connectionService->update([
            'status' => ConnectionService::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);
        return $connectionApplication;
    }

    public function initInternetServiceInfo(ConnectionApplication $application)
    {
        if (!$application->internetServiceInfo) {
            $connectionService = $application->connectionServices()->where('service_type', 'internet')->first();
            $plan = GoodtelPlan::where('is_active', true)
                ->where('name', $application->plan_type)->first();

            $application->internetServiceInfo()->create([
                'connection_application_id' => $application->id,
                'connection_service_id' => $connectionService->id ?? null,
                'goodtel_plan_id' => $plan->id ?? null,
                'is_shipping_same' => true,
                'unit_number' => $application->unit_number,
                'street_number' => $application->street_number,
                'street_name_only' => $application->street_name_only,
                'address_text' => $application->address_text,
                'street_address' => $application->street_address,
                'street_type' => $application->street_type,
                'city' => $application->city,
                'postcode' => $application->postcode,
                'state' => $application->state,
            ]);
        }

        return $application->refresh();
    }
}
