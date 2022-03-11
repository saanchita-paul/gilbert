<?php


namespace App\Services\Address;

use Illuminate\Support\Facades\Http;
use App\Services\Address\AddressModel;
use Exception;

class GBGServices
{

    /**
     * @var null
     */
    public function __construct()
    {
        $this->cleansingUrl = "https://hosted.mastersoftgroup.com/harmony/rest/au/cleanse/address";
    }

    public function setPayload( AddressModel $address ) 
    {
        $this->payload = [
            "payload" => [
                [
                    "fullAddress" => $address->getAddressText(),
                    "flatUnitNumber" => $address->getUnitNumber(),
                    "streetNumber" => $address->getStreetNumber(),
                    "streetName" => $address->getStreetName(),
                    "streetType" => $address->getStreetType(),
                    "postcode" => $address->getPostcode(),
                    "locality" => $address->getCity(),
                    "state" => $address->getState(),
                    "country" => "AU"
                ]
            ],
            "sourceOfTruth" => "GNAF"
        ];
    }

    private function checkException(GBGModel $gbg)
    {
        if($gbg?->unknown !== "" || $gbg?->exception !== null )
        {
            throw new Exception("Address not found");
        }
    }

    public function findAddressByText(?string $text = null, ?LookUpOptions $options = null): AddressModel
    {
        $authorization = 'Basic ' . base64_encode(config('address.gbgUserId') . ':' . config('address.gbgPassword'));
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $authorization
        ])
            ->withBody(json_encode($this->payload), 'application/json')
            ->post($this->cleansingUrl);
        // echo $response->status();
        $address = json_decode($response->body(), true);

        dd($address);

        $gbgModel = new GBGModel($address);
        $this->checkException($gbgModel);
        echo $gbgModel->getConnectionApplicationVersion()->getState();
        return $gbgModel->getConnectionApplicationVersion();
    }
}
