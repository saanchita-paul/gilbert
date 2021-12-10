<?php

namespace TSA\Services;

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\APILog;

class TsaSendAppliationService
{
    public function __construct(int $id) {
        $this->application = ConnectionApplication::findOrFail($id);
    }

    public function sendApplication()
    {
        $url = \config('tsa.root_url') . \config('tsa.insert_url');
        $url = APILog::setLoggerQuery($url, APILog::API_TSA_INSERT_DATA, false);
        
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'X-API-Service' => \config('tsa.x_api_service_name'),
            'X-API-Token' => \config('tsa.x_api_token')
        ])
            ->withBody(json_encode($this->getApplicationData()), 'application/json')
            ->post($url);
        
        info('TSA Request Data',$this->getApplicationData());
        info('TSA Response Data',json_decode($response->body(), true));
        return $this;
    }

    private function getApplicationData()
    {
        $lead = $this->application;
        return [
            "leads" => [
                [
                    "id" => (string)$lead->id,
                    "name" => $this->getName($lead),
                    "timezone" => "Australia/Melbourne",
                    "priority" => 1,
                    "start_timestamp" => date(DATE_ATOM),
                    "expiration_timestamp" => date(DATE_ATOM, strtotime("+2 days")),
                    "endpoints" => [
                        $lead->phone,
                    ],
                    "attributes" => [
                        "email" => $lead->email, 
                        "hood_crm_deeplink" => \config('tsa.app_url').'/applications/'.$lead->id,
                        "address" => $lead->address_text,
                        "postcode" => $lead->postcode,
                        "suburb" => $lead->city,
                        "service_interests" => $this->getInterests($lead),
                        "connection_date" => $this->getConnectionDate($lead->moving_date),
                        "real_estate_agent" => $lead->createdBy?->first_name.' '. $lead->createdBy?->last_name,
                        "real_estate_agency" => $lead->office?->name,
                    ]
                ]
            ]
        ];
    }

    private function getName($lead): string
    {
        return ($lead->first_name ?? $lead->first_name)
            . (' '. $lead->middle_name ?? '')
            . (' '. $lead->last_name ?? '');
    }

    private function getInterests($lead): string
    {
        $interests = [];
        $services = $lead->connectionServices;

        foreach ($services as $service) {
            $interests[] = $service->service_type;
        }
        return implode(", ",$interests);
    }

    private function getConnectionDate($movingDate): string
    {
        return date(DATE_ATOM, strtotime($movingDate));
    }
}
