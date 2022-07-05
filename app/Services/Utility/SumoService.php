<?php

namespace App\Services\Utility;

use Exception;
use Carbon\Carbon;
use App\Models\APILog;
use App\Models\Identification;
use function PHPSTORM_META\map;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;

use Illuminate\Support\Str;


class SumoService
{
    private array|Collection|ConnectionApplication|Model $application;

    const TYPE_MOBILE = 1;
    const TYPE_EMAIL  = 2;


    const MAP_STATE = [
        "New South Wales" => 'NSW',
        "Victoria" => 'VIC',
        "Queensland" => 'QLD',
        "South Australia" => 'SA',
        "Northern Territory" => 'NT',
        "Tasmania" => 'TAS',
        "Australian Capital Territory" => 'ACT',
        "Western Australia" => 'WA',
    ];

    const MAP_TYPE = [
        1 => 'Passport',
        2 => 'DriversLicence',
        3 => 'Medicare',
    ];

    const MAP_SERVICE = [
        'gas' => 'Gas',
        'power' => 'Electricity',
    ];

    const MAP_PROPERTY_TYPE = [
        1 => 'Residential',
        2 => 'Business',
    ];

    const MAP_PHONE = [
        "New South Wales" => '02',
        "Victoria" => '03',
        "Queensland" => '07',
        "South Australia" => '08',
        "Northern Territory" => '08',
        "Tasmania" => '03',
        "Australian Capital Territory" => '02',
        "Western Australia" => '08', // TODO recheck on documentation
    ];
    private string $submitType;

    /**
     * Store customer data in Sumo
     *
     * @throws \Exception
     */
    public function storeCustomerData(int $id, string $submitType)
    {
        try {
            $this->application = ConnectionApplication::findOrFail($id);
            $this->application->load(['identification', 'connectionServices', 'authorizedPerson']);
            $this->submitType = $submitType;


            $url = config('sumo.base_url').config('sumo.store_customer_data_url');
            $url = APILog::setLoggerQuery($url, APILog::API_SUMO_SUBMIT_LEAD, extend: false);

            $response = Http::put($url, $this->getCustomerData());
            // if (!$response->successful()) {
            //     ConnectionApplication::where('id', $this->application->id)->update([
            //         'is_running_submission' => 0,
            //     ]);
            // }

            return json_decode($response->body(), true);
        } catch (Exception $exception) {
            // ConnectionApplication::where('id', $this->application->id)->update([
            //     'is_running_submission' => 0,
            // ]);
            throw $exception;
        }

    }


    private function getCustomerData(): array
    {
        return array_merge($this->getIdDetails(), [
            'acceptTerms' => true,
//            'billDelivery' => $this->application->is_email_billing,
            'billDelivery' => true,
            'concentCC' => $this->application->is_contacted,
            'customerDateOfBirth' => $this->application->dob,
            'customerEmail' => $this->application->email,
            'customerFirstName' => $this->application->first_name,
            'customerLastName' => $this->application->last_name,
            'customerPhone' => $this->getMappedPhone($this->application->state, $this->application->phone_type, $this->application->phone),
            'customerTitle' => $this->application->title,
            //todo Why are we sending all services, do we need to check only what is submitted?
            'interestedIn' => $this->getMappedService($this->application->connectionServices?->pluck('service_type')->toArray()),
             'lifeSupport' => $this->getLifeSupport($this->submitType),
            // 'lifeSupportFuel' => "string",
            'marketingConcent' => $this->application->is_contacted == 1 ? true : false,
            'mirn' => $this->application->mirn,
            'nmi' => $this->application->nmi,
            'proposedMovingDate' => $this->getMappedDate($this->application->moving_date),
            'prospectType' => $this->getMappedPropertyType($this->application->property_type),
            'quoteNumber' => 'hood_'.$this->application->sumo_uuid,
            'secondaryCustomerEmail' =>  $this->application->authorizedPerson?->email,
            'secondaryCustomerFirstName' => $this->application->authorizedPerson?->first_name,
            'secondaryCustomerLastName' => $this->application->authorizedPerson?->last_name,
            'secondaryCustomerPhone' => $this->application->authorizedPerson?->phone,
            'secondaryCustomerDateOfBirth' => $this->application->authorizedPerson?->dob ? (new Carbon( $this->application->authorizedPerson?->dob ))->format('Y-m-d') : null,
            'secondaryCustomerTitle' => $this->application->authorizedPerson?->title,
        ]);
    }

    private function getIdDetails(): array
    {

        $id = [
            'authenticationExpiry' => $this->application->identification?->expire_date,
            'authenticationNo' => $this->application->identification?->card_number,
            'authenticationType' => $this->getAuthenticationType($this->application->identification?->type)
        ];

        /**
         * TODO:
         * we do not need State for driver license but sumo throwing error if we don't send "authenticationState"
         * property or send its value as null. So for now we are sending  static state value.
         */
        $id['authenticationState'] = $this->application->identification?->state ?  $this->getMappedState($this->application->identification?->state) : "VIC";
//
//        if ($this->application->identification?->state === Identification::TYPE_DRIVING_LICENCE) {
//            $id['authenticationState'] = $this->getMappedState($this->application->identification?->state);
//        }

        return $id;
    }


    private function getMappedState($state): string
    {
        return $state ? SumoService::MAP_STATE[$state] : '';
    }

    private function getAuthenticationType(?int $type): string
    {
        return $type ? SumoService::MAP_TYPE[$type] : '';
    }

    private function getMappedService($services): array
    {
        $data = [];
        foreach ( $services as $service) {
            if (isset(SumoService::MAP_SERVICE[$service])) {
                $data[] =  SumoService::MAP_SERVICE[$service];
            }
        }
        return $data;
    }

    private function getMappedDate($date)
    {
        $oDate = new \DateTime($date);
        return $oDate->format("Y-m-d");
    }

    private function getMappedPropertyType(?int $type): string
    {
        return $type ? SumoService::MAP_PROPERTY_TYPE[$type] : '';
    }

    private function getMappedPhone($state, $type, $phone): string
    {
        return $type === 1 ? $phone : SumoService::MAP_PHONE[$state].$phone;
    }

    public function validateEmail(String $email){
        $baseUrl              = config('sumo.base_url');
        $url                  = $baseUrl . "/validation/email";
        $emailTobeChecked     = $email;

        try {

            $response = Http::withHeaders([
                "content-type"    => "application/json",
                "Accept"          => "*/*",
            ])
            ->get($url, [ 'email' => $emailTobeChecked ]);

            $result = json_decode($response->body(), true);
            info('Result fetching from ignite');
            \Log::info($result);
            \Log::info($result['valid']);
            return $result['valid'];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }

    }

    /**
     * Validate email and phone using sumo API.
     *
     * @param String $data
     * @param String $type [ ex. mobile / email ]
     * @return ConnectionApplication $newApplication
     * @throws Exception $exception
     */
    public function validateData(String $data , String $type){
        $baseUrl              = config('sumo.base_url');
        $url                  = $baseUrl . "/validation" . '/' .  strtolower(  $type );
        $dataTobeChecked      = $data;

        try {

            $response = Http::withHeaders([
                "content-type"    => "application/json",
                "Accept"          => "*/*",
            ])
            ->get($url, [ $type => $dataTobeChecked ]);

            $result = json_decode($response->body(), true);
            info('Result fetching from sumo server');
            \Log::info($result);
            \Log::info($result['valid']);
            return $result['valid'];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }

    }

    public function saveStatus($applicationId, $status , $credit, $submitType)
    {
        $services = match ($submitType) {
            'energy' => [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY],
            'power' => [ConnectionService::TYPE_ELECTRICITY],
            'gas' => [ConnectionService::TYPE_GAS]
        };

        $status = strtolower($status);
        if($status == 'success'){
            ConnectionService::whereIn('service_type' , $services)
            ->where('provider_name', 'sumo')
            ->where('connection_application_id', $applicationId)
            ->update(['status' =>  ConnectionService::STATUS_ENERGY_SUBMIT ]);
        } else if($status == 'failed'){
            ConnectionService::whereIn('service_type' , $services)
            ->where('provider_name', 'sumo')
            ->where('connection_application_id', $applicationId)
            ->update(['status' =>  ConnectionService::STATUS_REJECTED, 'rejected_at' => now()]);
        }
    }

     private function getLifeSupport($submitType)
     {
         $life_support = 0;

         if ($submitType === 'gas'){
             $life_support =  $this->application->is_gas_life_support;
         }
         elseif ($submitType === 'power') {
             $life_support =  $this->application->is_power_life_support;
         }
         elseif ($submitType === 'energy') {
             if ($this->application->is_power_life_support == 1 || $this->application->is_gas_life_support == 1) {
                 $life_support = 1;
             }
         }
         return $life_support;
     }

}
