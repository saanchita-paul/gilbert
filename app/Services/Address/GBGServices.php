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
    public function __construct(private AddressModel $addressModelPayload)
    {
        $this->cleansingUrl = "https://hosted.mastersoftgroup.com/harmony/rest/au/cleanse/address";
        $this->setPayload();
    }

    private function setPayload() 
    {
        $this->payload = [
            "payload" => [
                [
                    "fullAddress" => $this->addressModelPayload->getAddressText(),
                    "flatUnitNumber" => $this->addressModelPayload->getUnitNumber(),
                    "streetNumber" => $this->addressModelPayload->getStreetNumber(),
                    "streetName" => $this->addressModelPayload->getStreetName(),
                    "streetType" => $this->addressModelPayload->getStreetType(),
                    "postcode" => $this->addressModelPayload->getPostcode(),
                    "locality" => $this->addressModelPayload->getCity(),
                    "state" => $this->addressModelPayload->getState(),
                    "country" => "AU"
                ]
            ],
            "sourceOfTruth" => "GNAF"
        ];
    }

    private function isAddressValid(GBGModel $gbg)
    {
        if($gbg?->unknown !== "" || $gbg?->exception !== null || $gbg?->streetName === null || $gbg?->streetName === "" )
        {
            return false;
            // throw new Exception("Address not found");
        } 
        return true;
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

        if($response->status() == 200)
        {
            return $this->setModelAndProperty($response);
        } 
        else {
            $this->addressModelPayload->setIsAddressComplete(false);
            return $this->addressModelPayload;
        }
        
    }

    private function setModelAndProperty($response){
        $address = json_decode($response->body(), true);
        // dd($address);
        info("gbg response data" , [ "gbg data" => $response->body()]);
        $gbgModel = new GBGModel($address);
        // echo $gbgModel->getConnectionApplicationVersion()->getState();
        $appAddress = $gbgModel->getConnectionApplicationVersion();
        $appAddress->setIsAddressComplete( $this->isAddressValid($gbgModel) );
        // info("is address complete" , )
        // dd($appAddress);
        return $appAddress;
    }
}
