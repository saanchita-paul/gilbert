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

            if ($response->status() == 200) {
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

        if (!$callHistoryJsonString) {
            return false;
        }

        $callHistory = json_decode($callHistoryJsonString, true);

        $connection_application->update(['tsa_call_status' => $callHistory['lead_status']]);

        $attemps = $callHistory['attempts'];


        $ids = collect($attemps)->pluck('attempt_id')->toArray();
        dump(sizeof($ids));

        $foundAttempts = TSACallHistory::query()
            ->select(['attempt_id'])
            ->where('connection_application_id', $connection_application->id)
            ->whereIn('attempt_id', $ids)
            ->get()
            ->pluck('attempt_id')
            ->toArray();


        $newAttempts = [];
        try {
            foreach ($attemps as $value) {
//                if (!in_array(data_get($value, 'attempt_id'), $foundAttempts)) {
                if (true) {

                    $newAttempts[] = [
                        'connection_application_id' => $connection_application->id,
                        'attempt_id' => data_get($value, 'attempt_id'),
                        'tsa_id' => data_get($value, 'attempt_id'),
                        'lead_status' => data_get($value, 'lead_status'),
                        'num_attempts' => data_get($value, 'num_attempts'),
                        'attempt_assigned_timestamp' => Carbon::parse($value['assigned_timestamp'])->format("Y-m-d H:i:s"),
                        'attempt_initiated_timestamp' => Carbon::parse($value['initiated_timestamp'])->format("Y-m-d H:i:s"),
                        'attempt_connected_timestamp' => Carbon::parse($value['connected_timestamp'])->format("Y-m-d H:i:s"),
                        'attempt_disconnected_timestamp' => Carbon::parse($value['disconnected_timestamp'])->format("Y-m-d H:i:s"),
                        'attempt_disposed_timestamp' => Carbon::parse($value['disposed_timestamp'])->format("Y-m-d H:i:s"),
                        'attempt_outcome' => data_get($value, 'outcome'),
                        'attempt_disposition_code' => data_get($value, 'disposition_code'),
                        'attempt_disposition_sub_code' => data_get($value, 'disposition_sub_code'),

                    ];
                }
            }
            dump(sizeof($newAttempts));

            TSACallHistory::query()->insert($newAttempts);

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            dump($exception->getMessage());
        }

    }

    public function saveCallHistoryBySchedule()
    {
        $connection_applications = ConnectionApplication::whereNotIn('status', [
            ConnectionApplication::STATUS_CLOSED,
            ConnectionApplication::STATUS_REJECTED,
            ConnectionApplication::STATUS_SUBMITTED,
        ])
            ->whereIn('id', [19240])
            ->whereNotNull('tsa_lead_id')
            ->get();

        foreach ($connection_applications as $connection_application) {
            $this->saveCallHistory($connection_application);
        }
    }


}
