<?php

namespace App\Services\Utility;

use App\Models\APILog;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SumoService
{
    private array|Collection|ConnectionApplication|Model $application;

    public function __construct(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices']);
    }

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
}
