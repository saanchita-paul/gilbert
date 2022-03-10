<?php


namespace App\Services\Address;

use Illuminate\Support\Facades\Http;
use App\Services\Address\AddressModel;

class GBGServices{

    /**
     * @var null
     */
    private $street_number;
    private GBGModel $gbgModel;
    private AddressModel $addressModel;

    public function __construct()
    {
        $this->cleansingUrl = "https://hosted.mastersoftgroup.com";
    }

    public function findAddressByText(?string $text = null, ?LookUpOptions $options = null) : AddressModel 
    {

        // {"payload":[{"fullAddress": "U 101 100 PLENTY RD, PRESTON VIC 3072","country": "AU"}],"sourceOfTruth": "GNAF"}
        // $payload = [ "payload" =>  [ "fullAddress" => "U 101 100 PLENTY RD, PRESTON VIC 3072","country" => "AU" ] , "sourceOfTruth" => "GNAF" ];

        // $authorization = 'Basic ' . base64_encode('hoodmovetech_test_user:f3se7N14GrCxHWQDgAJTu7wluFw7jDW9');
        // // $authoriza'Basic ' . 'aG9vZG1vdmV0ZWNoX3Rlc3RfdXNlcjpmM3NlN04xNEdyQ3hIV1FEZ0FKVHU3d2x1Rnc3akRXOQ==';
        // // $authorization = 'Basic ' . 'aG9vZG1vdmV0ZWNoX3Rlc3RfdXNlcjpmM3NlN04xNEdyQ3hIV1FEZ0FKVHU3d2x1Rnc3akRXOQ==';
        // $response = Http::withHeaders([
        //     // 'Authorization' => $authorization,
        //     'auth' => [ 'hoodmovetech_test_user', 'f3se7N14GrCxHWQDgAJTu7wluFw7jDW9' ]
        // ])
        //     ->withBody(json_encode($payload , true), 'application/json')
        //     ->post($this->cleansingUrl);
        
        // info('TSA Response Data', [json_decode($response->body(), true) ]);
        // info('payload',[ json_encode($payload , true) ]);
        

        // $client = new \GuzzleHttp\Client(['base_uri' => $this->cleansingUrl ]);
        
        // $response =  $client->request('POST', '/harmony/rest/au/cleanse/address', [ 'headers' => [ 'Content-Type' => 'application/json', 'Authorization' => $authorization ] ,  'body' => json_encode($payload) ]);
        
        // info('TSA Response Data', [json_decode($response->getBody(), true) ]);
        // $client = new \GuzzleHttp\Client();
        
        // $response =  $client->request('POST', $this->cleansingUrl, ['body' => json_encode($payload , true) ,  'auth' => ['hoodmovetech_test_user', 'f3se7N14GrCxHWQDgAJTu7wluFw7jDW9'] ]);
        // info('TSA Response Data', [json_decode($response->getBody(), true) ]);

        // print(json_decode($response->body());

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://hosted.mastersoftgroup.com/harmony/rest/au/cleanse/address',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"payload":[{"fullAddress": "U 101 100 PLENTY RD, PRESTON VIC 3072","country": "AU"}],"sourceOfTruth": "GNAF"}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic aG9vZG1vdmV0ZWNoX3Rlc3RfdXNlcjpmM3NlN04xNEdyQ3hIV1FEZ0FKVHU3d2x1Rnc3akRXOQ=='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        info("response" , ['res' => $response]);
        

        $this->addressModel = new AddressModel();
        return $this->addressModel;
    }

}