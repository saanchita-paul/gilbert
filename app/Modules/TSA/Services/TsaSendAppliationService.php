<?php

namespace TSA\Services;

use Carbon\Carbon;
use App\Models\APILog;
use App\Models\TSACallHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;

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


    public function getCallHistory()
    {
        $url = \config('tsa.root_url') . \config('tsa.call_history') . $this->application->id;
        $url = APILog::setLoggerQuery($url, APILog::API_TSA_INSERT_DATA, false);
        
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'X-API-Service' => \config('tsa.x_api_service_name'),
            'X-API-Token' => \config('tsa.x_api_token')
        ])
            ->get($url);
        
        // $responseData = json_decode($response->body(), true);
        // $this->saveTSAId($responseData);
        
        $this->saveCallHistory($response->body());
        // info('TSA Request Data', $this->getApplicationData());
        // info('TSA Response Data', $responseData);
        return $this;
    }

    private function saveCallHistory($callHistories)
    {
        $callHistory = json_decode($callHistories, true);
        $attemps = $callHistory['attempts'];

        info('cuhnkk', [$callHistory['attempts']]);
        foreach ($attemps as $key => $value) {
            # code...
            $tsaCallHistory = new TSACallHistory();
            $tsaCallHistory->all_fields_dump = $callHistories;
            $tsaCallHistory->connection_application_id = $this->application->id;
            $tsaCallHistory->tsa_id = $callHistory['import_id'];
            $tsaCallHistory->lead_status = $callHistory['lead_status'];
            $tsaCallHistory->num_attempts = $callHistory['num_attempts'];

            $tsaCallHistory->attempts_id = $value['attempt_id'];
            $tsaCallHistory->attempts_assigned_timestamp = Carbon::parse($value['assigned_timestamp'])->format("Y-m-d H:i:s")  ;
            $tsaCallHistory->attempts_initiated_timestamp = Carbon::parse($value['initiated_timestamp'])->format("Y-m-d H:i:s");
            $tsaCallHistory->attempts_connected_timestamp = Carbon::parse($value['connected_timestamp'])->format("Y-m-d H:i:s");
            $tsaCallHistory->attempts_disconnected_timestamp = Carbon::parse($value['disconnected_timestamp'])->format("Y-m-d H:i:s");
            $tsaCallHistory->attempts_disposed_timestamp = Carbon::parse($value['disposed_timestamp'])->format("Y-m-d H:i:s");
            $tsaCallHistory->attempts_outcome = $value['outcome'];
            $tsaCallHistory->attempts_disposition_code = $value['disposition_code'];
            $tsaCallHistory->attempts_disposition_sub_code = $value['disposition_sub_code'];
            
            $tsaCallHistory->save();
        }

        // info("print call history", [$callHistory]);
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
}
