<?php

namespace App\Services\Utility;

use App\Models\APILog;
use App\Models\ConnectionApplication;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Identification;
use App\Models\ConnectionApplicationSecondaryACC;

use function PHPSTORM_META\map;

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
    ];

    /**
     * Store customer data in Sumo
     *
     * @throws \Exception
     */
    public function storeCustomerData(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices', 'authorizedPerson']);

        $url = config('sumo.base_url').config('sumo.store_customer_data_url');
        $url = APILog::setLoggerQuery($url, APILog::API_SUMO_SUBMIT_LEAD, extend: false);

        $response = Http::put($url, $this->getCustomerData());

        return json_decode($response->body(), true);
    }

    private function getCustomerData()
    {
        return [
            'acceptTerms' => true,
            'authenticationExpiry' => $this->application->identification?->expire_date,
            'authenticationNo' => $this->application->identification?->card_number,
            'authenticationState' => $this->getMappedState($this->application->identification?->state),
            'authenticationType' => $this->getAuthenticationType($this->application->identification?->type),
//            'billDelivery' => $this->application->is_email_billing,
            'billDelivery' => true,
            'concentCC' => $this->application->is_contacted,
            'customerDateOfBirth' => $this->application->dob,
            'customerEmail' => $this->application->email,
            'customerFirstName' => $this->application->first_name,
            'customerLastName' => $this->application->last_name,
            'customerPhone' => $this->getMappedPhone($this->application->state, $this->application->phone_type, $this->application->phone),
            'customerTitle' => $this->application->title,
            'interestedIn' => $this->getMappedService($this->application->connectionServices?->pluck('service_type')->toArray()),
            'lifeSupport' => false,
            // 'lifeSupportFuel' => "string",
            'marketingConcent' => false,
            'mirn' => $this->application->mirn,
            'nmi' => $this->application->nmi,
            'proposedMovingDate' => $this->getMappedDate($this->application->moving_date),
            'prospectType' => $this->getMappedPropertyType($this->application->property_type),
            'quoteNumber' => $this->application->id,
            'secondaryCustomerEmail' =>  $this->application->authorizedPerson?->email,
            'secondaryCustomerFirstName' => $this->application->authorizedPerson?->first_name,
            'secondaryCustomerLastName' => $this->application->authorizedPerson?->last_name,
            'secondaryCustomerPhone' => $this->application->authorizedPerson?->phone,
            'secondaryCustomerTitle' => $this->application->authorizedPerson?->title,
        ];
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

}
