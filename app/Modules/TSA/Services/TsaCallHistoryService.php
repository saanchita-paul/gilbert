<?php

namespace TSA\Services;

use Carbon\Carbon;
use App\Models\APILog;
use App\Models\TSACallHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use Exception;

class TsaCallHistoryService
{
    
    private function getCallHistory($connection_application)
    {
        try {
            // $this->application = ConnectionApplication::where('id', $connection_application_id)->whereNotNull('tsa_id')->firstOrFail();

            $url = \config('tsa.root_url') . \config('tsa.call_history') . $connection_application->tsa_id;
            $url = APILog::setLoggerQuery($url, APILog::API_TSA_INSERT_DATA, false);
            
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'X-API-Service' => \config('tsa.x_api_service_name'),
                'X-API-Token' => \config('tsa.x_api_token')
            ])
                ->get($url);
            info("status" , [$response->status()]);
            
            if($response->status() == 200) {
                return $response->body();
            }
            throw new Exception("no call history found");

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            return false;
        }
        
    }

    public function saveCallHistory($connection_application)
    {
        
        $callHistoryString = $this->getCallHistory($connection_application);
        
        if(!$callHistoryString) {
            return false;
        }

        $callHistory = json_decode($callHistoryString, true);
        info('TSA Call History', [$callHistory]);
        $attemps = $callHistory['attempts'];
 
        
        foreach ($attemps as $key => $value) {
            try {
                TSACallHistory::where('attempt_id', $value['attempt_id'])->firstOrFail();
            } catch (\Exception $exception) {
                $tsaCallHistory = new TSACallHistory();
                $tsaCallHistory->all_fields_dump = $callHistoryString;
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
            
        }

    }

    public function saveCallHistoryBySchedule()
    {
        $connection_applications = ConnectionApplication::whereNotIn('status', [
            ConnectionApplication::STATUS_CLOSED,
            ConnectionApplication::STATUS_REJECTED,
            ConnectionApplication::STATUS_SUBMITTED,
        ])
        ->whereNotNull('tsa_id')
        ->get();
        foreach ($connection_applications as $connection_application) {
            $this->saveCallHistory($connection_application);
        }
    }
}
