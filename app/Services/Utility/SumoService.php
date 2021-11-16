<?php

namespace App\Services\Utility;

use App\Models\APILog;
use App\Models\ConnectionApplication;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class SumoService
{
    private array|Collection|ConnectionApplication|Model $application;

    const TYPE_MOBILE = 1;
    const TYPE_EMAIL  = 2;



    /**
     * Store customer data in Sumo
     *
     * @throws \Exception
     */
    public function storeCustomerData()
    {
        $url = config('sumo.base_url').config('sumo.store_customer_data_url');

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => '*/*',
        ])
            ->withBody(json_encode($this->getCustomerData()), 'application/json')
            ->put($url);

        dd(json_decode($response->body(), true));
    }

    private function getCustomerData()
    {
        return [
            'customerDataDto' => [
                'acceptTerms' => true,
                'authenticationExpiry' => "string",
                'authenticationNo' => "string",
                'authenticationState' => "ACT",
                'authenticationType' => "string",
                'billDelivery' => true,
                'concentCC' => "string",
                'customerDateOfBirth' => "2021-11-15",
                'customerEmail' => "test@mail.com",
                'customerFirstName' => "string",
                'customerLastName' => "string",
                'customerPhone' => "string",
                'customerTitle' => "string",
                'interestedIn' => [
                    "Electricity"
                ],
                'lifeSupport' => true,
                'lifeSupportFuel' => "string",
                'marketingConcent' => true,
                'mirn' => "string",
                'nmi' => "string",
                'proposedMovingDate' => "2021-11-15",
                'prospectType' => "Business",
                'quoteNumber' => "string",
                'secondaryCustomerEmail' => "string",
                'secondaryCustomerFirstName' => "string",
                'secondaryCustomerLastName' => "string",
                'secondaryCustomerPhone' => "string",
                'secondaryCustomerTitle' => "string",
            ],
        ];
    }

    /**
     * Validate email and phone using sumo API.
     *
     * @param  String $mobileOrEmail [ ex. riyad298@gmail.com / 01919448787 ]
     * @param  String $type [ ex. mobile / email ]
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
