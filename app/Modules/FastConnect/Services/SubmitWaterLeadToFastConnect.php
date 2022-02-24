<?php

namespace FastConnect\Services;

use App\Models\APILog;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\RejectionReason;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;


class SubmitWaterLeadToFastConnect
{
    private string $accessToken;
    private int $applicationId;
    private array|Collection|ConnectionApplication|Model $application;
    private $productServiceData;

    const MAP_PROPERTY_TYPE = [
        1 => 'rented',
        2 => 'owning'
    ];

    const MAP_TITLE = [
        'mr' => 'MR',
        'mrs' => 'MRS',
        'ms' => 'MS',
        'dr' => 'DR',
        'MISS' => 'MISS',
    ];

    const MAP_IDENTIFICATION_PROFILE_ID = [
        Identification::TYPE_DRIVING_LICENCE => 2,
        Identification::TYPE_MEDICARE => 1,
        Identification::TYPE_PASSPORT => 3
    ];

    const MAP_IDENTIFICATION_STATE = [
        "New South Wales" => 2,
        "Victoria" => 7,
        "Queensland" => 4,
        "South Australia" => 5,
        "Northern Territory" => 3,
        "Tasmania" => 6,
        "Australian Capital Territory" => 1,
        "Western Australia" => 8,
    ];

    const MAP_IDENTIFICATION_COUNTRY = [
        "AUS" => 13,
        "AUSTRALIA" => 13
    ];

    const MAP_COUNTRY = [
        "AUS" => 'Australia'
    ];

    const MAP_STATE = [
        "New South Wales" => 'NSW',
        "Victoria" => 'VIC',
        "Queensland" => 'QLD',
        "South Australia" => 'SA',
        "Northern Territory" => 'NT',
        "Tasmania" => 'TAS',
        "Australian Capital Territory" => 'ACT',
        'Western Australia' => 'WA'
    ];

    const mapLengthOfCountry = [ 2 => 'short_code' , 3 => 'country_code' , 4 => 'name' ];

    public function __construct(int $id) {
        $this->applicationId = $id;
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices', 'authorizedPerson']);
        $this->authenticate();
    }

    public function getCountries(){
        try {
            $authorization = 'Bearer ' . $this->accessToken;
            $url = config('fastconnect.root_url') . config('fastconnect.get_countries');
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => $authorization
            ])
            ->get($url);
            $countries = json_decode($response->body(), true);
            return $countries;
        } catch (\Exception $exception) {
            info('exception in getCountires' , [ $exception->getTraceAsString() , $exception->getMessage() ]);
            return [];
            //throw $th;
        }

    }

    public function authenticate(): static
    {
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'authorization' => \config('fastconnect.base64_key'),
        ])
            ->post( \config('fastconnect.root_url') . \config('fastconnect.get_water_token_uri'));

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
    }

    private function getCompparedDate($requestedDate, $availableDate)
    {
        $format = 'Y/m/d H:i:s';
        $availableDateCarbon = Carbon::createFromFormat($format, Carbon::parse($availableDate)->format($format));
        $requestedDateCarbon = Carbon::createFromFormat($format, Carbon::parse($requestedDate)->format($format));

        return $requestedDateCarbon->gte($availableDateCarbon) ? $requestedDateCarbon->format('Y-m-d') : $availableDateCarbon->format('Y-m-d') ;
    }

    private function getNextAvailableDate(){
        return $this->productServiceData->productDetails[0]['sub_groups'][0]['products'][0]['next_available_date'];
    }

    private function getMovingDate(){
        return $this->getCompparedDate($this->application->moving_date, $this->getNextAvailableDate());
    }

    public function submitWaterLead()
    {
        $productService = new FastConnectProductService($this->applicationId);
        $this->productServiceData = $productService->getProductGroup()->getProductDetails();

        $mappedData = $this->getData($this->application);
        $mappedAllData = $this-> addExtraData($this->application, $mappedData);

        info("Water Submit request body");
        info(json_encode($mappedData));
        info("Water Submit request body");

        $authorization = 'Bearer ' . $this->accessToken;

        $url = config('fastconnect.root_url') . config('fastconnect.submit_water_lead_url');
        $url = APILog::setLoggerQuery($url, APILog::API_FAST_CONNECT_WATER_SUBMIT, false);

        info($url);
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => $authorization
        ])
            ->withBody(json_encode($mappedAllData), 'application/json')
            ->post($url);


        info("Water submit response body");
        info($response->body());
        info("Water submit response body");

        if($response->status() === 403)
        {
            info("Saving Water Failed Response");
            $this->saveRejectionReason($response->body(), $this->application->id, 'water');
        }

        if($response->status() === 201)
        {
            info("Deleting old reasons");
            $this->deleteOldRejectionReasons($this->application->id, 'water');
        }
        
        info("nextAvailableDatebody ");
        info($this->getNextAvailableDate());
        info("nextAvailableDatebody");

        $this->application->update(['water_submit_response' => empty($response->body()) ? null : $response->body(), 'water_next_available_date' => $this->getMovingDate() ]);

        return json_decode($response->body());
    }

    public function getPhoneNumber(){
        return $this->application->phone_type == ConnectionApplication::PHONE_TYPE_MOBILE && !is_null($this->application->phone) ? $this->application->phone : $this->application->homephone;
    }

    public function getData($lead)
    {
        return [
            "agent_code" => "3954V",
            "application_type" => "RESIDENTIAL",
            "locale" => "en",
            "address" => [
                "move_in_address" => [
                    "street_number" => $lead->street_number,
                    "street_name" => $lead->street_name,
                    "street_type" => $lead->getRoadType(),
                    "suburb" => $lead->city,
                    "state" => $this->getMappedState($lead->state),
                    "post_code" => $lead->postcode,
                    // "property_type" => "old",
                    "property_ownership" => $this->getMappedProperty($this->application->tenancy_type),
                    // "landlord_name" => "string",
                    // "landlord_suburb" => "string",
                    // "landlord_phone" => "string"
                ]
            ],
            "products" => [
                [
                    "id" => $this->productServiceData->productDetails[0]['sub_groups'][0]['products'][0]['id'],
                    "product_group_id" => $this->productServiceData->waterConnection->id,
                    "contract_id" => $this->productServiceData->productDetails[0]['sub_groups'][0]['products'][0]['contracts'][0]['id'],
                    "requested_date" => $this->getMovingDate(),
                    "marketing" => false
                ]
            ],
            "contact" => [
                "primary" => [
                    "title" => $this->getMappedTitle($lead->title),
                    "first_name" => $lead->first_name,
                    "middle_name" => $lead->middle_name ?? '',
                    "last_name" => $lead->last_name,
                    "date_of_birth" => $lead->dob,
                    "email" => $lead->email,
                    "phone_preference" => $this->getPhoneNumber(),
                    // "phone_alternate" => "0491570006",
                    "identification" => [
                        [
                            "identification_profile_item_id" => $this->getMappedIdentificationType($lead->identification->type),
                            "number" => $lead->identification->card_number,
                            "expiry" =>  $lead->identification->expire_date,
                        ]
                    ]
                ],
            ],
            "billing_location" => [
                "connection" => [
                  "location_type" => "ADDRESS",
                  "address" => [
                    // "unit_number" => "1",
                    // "lot_number" => "1",
                    "street_number" => $lead->street_number,
                    "street_name" => $lead->street_name,
                    "street_type" => $lead->getRoadType(),
                    "suburb" => $lead->city,
                    "state" => $this->getMappedState($lead->state),
                    "post_code" => $lead->postcode,
                    // "care_of" => "Bill Smith"
                  ]
                ]
            ]
        ];
    }

    private function getMappedDate($date)
    {
        $oDate = new \DateTime($date);
        return $oDate->format("Y-m-d");
    }

    private function getMappedProperty($tenancy): string
    {
        return $tenancy ? SubmitWaterLeadToFastConnect::MAP_PROPERTY_TYPE[$tenancy] : '';
    }

    private function getMappedTitle(?string $title): string
    {
        if ($title && isset(SubmitWaterLeadToFastConnect::MAP_TITLE[strtolower($title)])) {
            return SubmitWaterLeadToFastConnect::MAP_TITLE[strtolower($title)];
        }
        return "MR";
    }

    private function getMappedState($state): string
    {
        return $state ? SubmitWaterLeadToFastConnect::MAP_STATE[$state] : "";
    }

    private function getMappedIdentificationType($type): int
    {
        return SubmitWaterLeadToFastConnect::MAP_IDENTIFICATION_PROFILE_ID[$type];
    }

    private function getMappedIdentificationState($state)
    {
        return $state ?  SubmitWaterLeadToFastConnect::MAP_IDENTIFICATION_STATE[$state] : "";
    }

    public function getMappedIdentificationCountry($country)
    {
        try {
            $countries =  $this->getCountries();
            $lenghtOfName =  strlen($country) > 3 ? 4 : strlen($country);
            foreach ($countries as $val) {
                if ( strtolower( $val[SubmitWaterLeadToFastConnect::mapLengthOfCountry[$lenghtOfName]] ) === strtolower( $country) ) {
                    return $val['id'];
                }
            }
            throw new Exception("Country not found");
        } catch (\Exception $exception) {
            info('exception in getMappedIdentificationCountry, SubmitWaterLeadToFastConnect' , [ $exception->getTraceAsString() , $exception->getMessage() ]);
            throw new Exception("Country not found");
        }
    }

    private function addExtraData($lead, $data)
    {
        !is_null($lead->unit_number) ? $data['address']['move_in_address']['unit_number'] = $lead->unit_number : null;
        !is_null($lead->unit_number) ? $data['billing_location']['connection']['address']['unit_number'] = $lead->unit_number : null;
        switch ($lead->identification->type) {
            case 1: //TYPE_PASSPORT
              $data['contact']['primary']['identification'][0]['issuer_country_id'] =
                $this->getMappedIdentificationCountry($lead->identification->country);
              break;
            case 2: //TYPE_DRIVING_LICENCE
              $data['contact']['primary']['identification'][0]['issuer_state_id'] =
                $this->getMappedIdentificationState($lead->identification->state);
              break;
            case 3: //TYPE_MEDICARE
              $data['contact']['primary']['identification'][0]['medicare_color'] =
                $lead->identification->card_color;
              $data['contact']['primary']['identification'][0]['medicare_irn'] =
                (int)$lead->identification->special_number;
              $data['contact']['primary']['identification'][0]['issuer_country_id'] = 13;
              break;
        }
        if($lead->billing_street_address !== null) {
            !is_null($lead->billing_unit_number) ?
                $data['billing_location']['connection']['address']['unit_number']
                = $lead->billing_unit_number : null;
            !is_null($lead->billing_street_number) ?
                $data['billing_location']['connection']['address']['street_number']
                = $lead->billing_street_number : null;
            !is_null($lead->billing_street_name) ?
                $data['billing_location']['connection']['address']['street_name']
                = $lead->billing_street_name : null;
            !is_null($lead->billing_city) ?
                $data['billing_location']['connection']['address']['suburb']
                = $lead->billing_city : null;
            !is_null($lead->billing_state) ?
                $data['billing_location']['connection']['address']['state']
                = $this->getMappedState($lead->billing_state) : null;
            !is_null($lead->billing_postcode) ?
                $data['billing_location']['connection']['address']['post_code']
                = $lead->billing_postcode : null;
            !is_null($lead->billing_street_name) ?
                $data['billing_location']['connection']['address']['street_type']
                = $lead->getbillingRoadType() : null;
        }


        if($lead->authorizedPerson?->first_name && $lead->authorizedPerson?->email) {
            if($this->isInValidSecondaryContactExit($lead->authorizedPerson)) {
                return $data;
            }
            $data['contact']['secondary']['title'] = $this->getMappedTitle($lead->authorizedPerson->title) ?? "MR";
            $data['contact']['secondary']['first_name'] = $lead->authorizedPerson->first_name ?? "";
            $data['contact']['secondary']['middle_name'] = $lead->authorizedPerson->middle_name ?? "";
            $data['contact']['secondary']['last_name'] = $lead->authorizedPerson->last_name ?? "";
            $data['contact']['secondary']['date_of_birth'] = $this->getMappedDate($lead->authorizedPerson->dob);
            $data['contact']['secondary']['email'] = $lead->authorizedPerson->email ?? "";
            $data['contact']['secondary']['phone_preference'] = $lead->authorizedPerson->phone ?? "";
            $data['contact']['secondary']['identification'] = [$this->secondaryContactIdentification($lead->authorizedPerson)];
        }

        return $data;
    }

    private function isInValidSecondaryContactExit(?ConnectionApplicationSecondaryACC $secondaryContact)
    {

        if (!$secondaryContact) {
            return true;
        }
        $invalid = empty($secondaryContact->first_name)
            || empty($secondaryContact->last_name)
            || empty($secondaryContact->title)
            || empty($secondaryContact->dob)
            || empty($secondaryContact->email)
            || empty($secondaryContact->phone);

        if ($invalid) {
            return true;
        }


        return match ($secondaryContact?->identification_type) {
            Identification::TYPE_PASSPORT => empty($secondaryContact?->card_number)
                || empty($secondaryContact?->country)
                || empty($secondaryContact?->expire_date),
            Identification::TYPE_MEDICARE => empty($secondaryContact?->card_number)
                || empty($secondaryContact?->card_color)
                || empty($secondaryContact?->special_number)
                || empty($secondaryContact?->expire_date),
            Identification::TYPE_DRIVING_LICENCE => empty($secondaryContact?->card_number)
                || empty($secondaryContact?->state)
                || empty($secondaryContact?->expire_date),
            default => true,
        };
    }

        private function secondaryContactIdentification($secondaryContact)
        {
            $expireDate = (new Carbon($secondaryContact->expire_date))->format('Y-m-d');
            $identification = [
                'number' => $secondaryContact->card_number,
                'expiry' => $expireDate,
                'identification_profile_item_id' => $this->getMappedIdentificationType($secondaryContact->identification_type),
            ];

            $data = match($secondaryContact?->identification_type) {
                Identification::TYPE_PASSPORT => ['issuer_country_id' => $this->getMappedIdentificationCountry($secondaryContact->country)],
                Identification::TYPE_MEDICARE => [
                        'medicare_color' => $secondaryContact?->card_color,
                        'medicare_irn' => $secondaryContact->special_number,
                    ],

                Identification::TYPE_DRIVING_LICENCE => [
                        'issuer_state_id' => $this->getMappedIdentificationState($secondaryContact->state),
                    ],
                default => []
            };
            return array_merge($identification, $data);
        }
    /**
     * get ConnectionService builder
     *
     * @param int $leadId
     * @param $serviceType
     *
     * @return Builder
     */
    private function getServiceBuilder(int $leadId, $serviceType): Builder
    {
        return ConnectionService::query()
            ->where('connection_application_id', $leadId)
            ->where('service_type', $serviceType);
    }

    private function saveRejectionReason(string $data, int $leadId, string $serviceType)
    {
        /** @var ConnectionService $service */
        $service = $this->getServiceBuilder($leadId, $serviceType)->first();

        if ($service) {
            $service->reasons()->delete();
        }

        $rejectionReason = new RejectionReason();
        $rejectionReason->connection_application_id = $leadId;
        $rejectionReason->connection_service_id = $service?->id;
        $rejectionReason->service_type = $serviceType;
        $rejectionReason->reason_code = 'WATER_SUBMIT_FAILED';
        $rejectionReason->reason_text = $data;
        $rejectionReason->save();
    }

    private function deleteOldRejectionReasons(int $leadId, string $serviceType)
    {
        /** @var ConnectionService $service */
        $service = $this->getServiceBuilder($leadId, $serviceType)->first();

        if ($service) {
            $service->reasons()->delete();
        }
    }
}
