<?php

namespace TSA\Services;

use Carbon\Carbon;
use App\Models\APILog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use Exception;


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
        
        $responseData = json_decode($response->body(), true);
        $this->saveTSAId($responseData);

        info('TSA Request Data', $this->getApplicationData());
        info('TSA Response Data', $responseData);
        return $this;
    }

    private function saveTSAId($tsaData)
    {
        $this->application->tsa_id = $tsaData['import_id'];
        $this->application->save();
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
                    "expiration_timestamp" => date(DATE_ATOM, strtotime("+7 days")),
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


    public function getTsaLeadId()
    {
        try {
            $url = \config('tsa.root_url') . \config('tsa.tsa_lead_id') . '?external_id='.  $this->application->id;
            // $url = 'https://hood.tsagroup-tech.com/api/campaign/lead/search?external_id=H0010';
            
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'X-API-Service' => \config('tsa.x_api_service_name'),
                'X-API-Token' => \config('tsa.x_api_token')
            ])->get($url);
        
            if($response->status() == 200) {
                $responseData = json_decode($response->body(), true);
                return $responseData[0]['lead_id'];
            }
            throw new Exception("no call history found");

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            return false;
        }
        
    }
}
