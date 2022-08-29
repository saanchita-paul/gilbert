<?php

namespace Powershop\Services;

use App\Models\ConnectionService;
use App\Models\ConnectionApplication;
use App\Models\RejectionReason;

use Carbon\Carbon;
use Exception;

class HandleRejectionService
{
    const MAP_REJECTION_KEY = [
        'account_setup' => 'Property Type',
        'account_holder' => 'Personal Details',
        'login' => 'Personal Details',
        'property_information' => 'Property Details',
        'power' => 'Electricity',
        'gas' => 'Gas',
        'eligible_for_concessions' => 'Concessions',
        'payment_details' => 'Payment details',
        'secondary_account_holders' => 'Authorized Person',
        'vulnerabilities' => 'Life support details',
        'terms_and_conditions_accepted_at' => 'Terms and Conditions Approval Timestamp'
    ];

    public static function handleErrors(int $application_id, array $services, array $errors) {
        $conServices = ConnectionService::where('connection_application_id', $application_id)
                        ->whereIn('service_type', $services)
                        ->where('provider_name', ConnectionService::PROVIDER_POWER_SHOP)
                        ->get();

        if (count($conServices) == 0) {
            \Log::error('No connection service found to update rejection reasons');
        }
        else {
            foreach ($conServices as $conService) {
                foreach ($errors as $key => $val) {
                    if (in_array($key, $services)){
                        if ($conService->service_type == $key){
                            foreach ($val as $message){
                                $mapKeyToText = self::MAP_REJECTION_KEY[$key] ?? $key;
                                $errorMessage = $mapKeyToText . ' ' . $message;
                                self::saveRejectedStatus($conService->id, $key, $errorMessage);
                            }
                        }
                    }
                    else {
                        foreach ($val as $message){
                            $mapKeyToText = self::MAP_REJECTION_KEY[$key] ?? $key;
                            $errorMessage = $mapKeyToText . ' ' . $message;
                            self::saveRejectedStatus($conService->id, $key, $errorMessage);
                        }
                    }
                }
            }
        }
    }

    public static function saveRejectedStatus($serviceId, $errorCode = '', $errorMessage = '')
    {
        $service = ConnectionService::findOrFail($serviceId);
        $service->status = ConnectionService::STATUS_REJECTED;
        $service->rejected_at = Carbon::now();

        $service->save();

        if(!empty($errorCode) && !empty($errorMessage)){

            $newRejectReason = new RejectionReason();
            $newRejectReason->connection_service_id = $service->id;
            $newRejectReason->connection_application_id = $service->connection_application_id;
            $newRejectReason->service_type = $service->service_type;
            $newRejectReason->reason_code = $errorCode;
            $newRejectReason->reason_text = $errorMessage;

            $newRejectReason->save();
        }
    }

}
