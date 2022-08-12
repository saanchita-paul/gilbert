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

    public function getCallHistory($connection_application)
    {
        try {
            $url = \config('tsa.root_url') . \config('tsa.call_history') . $connection_application->tsa_lead_id;
            // $url = APILog::setLoggerQuery($url, APILog::API_TSA_SAVE_HISTORY, false); // no need log

            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'X-API-Service' => \config('tsa.x_api_service_name'),
                'X-API-Token' => \config('tsa.x_api_token')
            ])
            ->get($url);

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
        $callHistoryJsonString = $this->getCallHistory($connection_application);

        if(!$callHistoryJsonString) {
            return false;
        }

        $callHistory = json_decode($callHistoryJsonString, true);

        $connection_application->update(['tsa_call_status' => $callHistory['lead_status'] ]);

        $attemps = $callHistory['attempts'];

        $ids = collect($attemps)->pluck('attempt_id')->toArray();

        $foundAttempts = TSACallHistory::query()->select(['attempt_id'])->whereIn('attempt_id', $ids)->get()->pluck('attempt_id')->toArray();

        foreach ($attemps as $value) {
            try {
                if (!in_array(data_get($value, 'attempt'), $foundAttempts)) {
                    $tsaCallHistory = new TSACallHistory();
                    $tsaCallHistory->connection_application_id = $connection_application->id;
                    $tsaCallHistory->tsa_id = $callHistory['import_id'];
                    $tsaCallHistory->lead_status = $callHistory['lead_status'];
                    $tsaCallHistory->num_attempts = $callHistory['num_attempts'];

                    $tsaCallHistory->attempt_id = $value['attempt_id'];
                    $tsaCallHistory->attempt_assigned_timestamp = Carbon::parse($value['assigned_timestamp'])->format("Y-m-d H:i:s")  ;
                    $tsaCallHistory->attempt_initiated_timestamp = Carbon::parse($value['initiated_timestamp'])->format("Y-m-d H:i:s");
                    $tsaCallHistory->attempt_connected_timestamp = Carbon::parse($value['connected_timestamp'])->format("Y-m-d H:i:s");
                    $tsaCallHistory->attempt_disconnected_timestamp = Carbon::parse($value['disconnected_timestamp'])->format("Y-m-d H:i:s");
                    $tsaCallHistory->attempt_disposed_timestamp = Carbon::parse($value['disposed_timestamp'])->format("Y-m-d H:i:s");
                    $tsaCallHistory->attempt_outcome = $value['outcome'];
                    $tsaCallHistory->attempt_disposition_code = $value['disposition_code'];
                    $tsaCallHistory->attempt_disposition_sub_code = $value['disposition_sub_code'];

                    dump($tsaCallHistory->toArray());
//                    $tsaCallHistory->save();

                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());
                \Log::error($exception->getTraceAsString());
                dump($exception->getMessage());
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
            ->whereIn('id', [18124])

        ->whereNotNull('tsa_lead_id')
        ->get();

        foreach ($connection_applications as $connection_application) {
            $this->saveCallHistory($connection_application);
        }
    }



}
