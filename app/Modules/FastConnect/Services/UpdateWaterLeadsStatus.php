<?php


namespace App\Modules\FastConnect\Services;


use App\Jobs\WaterStatusUpdateJob;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Agency\UpdatedWaterStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateWaterLeadsStatus
{
    private $accessToken;
    public function __construct() {
        $this->authenticate();
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

    public function getAllSubmittedWaterLead() {
        $leads = ConnectionService::query()
            ->with('connectionApplication')
            ->where('service_type', 'water')
            ->whereHas('connectionApplication', function (Builder $lead) {
                $lead->whereNotNull('fast_connect_customer_reference');
            })
//            ->where('CS.status','=', ConnectionService::WATER_STATUS_IN_PROGRESS)
            ->get();

        info("[GetWaterService:getAllSubmittedWaterLead] Water leads", $leads->pluck('connection_application_id')->toArray());



        foreach ($leads as $lead) {

            if(!is_null($lead->connectionApplication?->fast_connect_customer_reference)) {
                WaterStatusUpdateJob::dispatch($lead->connection_application_id, $lead->connectionApplication->fast_connect_customer_reference);
            }

        }

    }

    public function getSubmittedDetails($id, $fast_connect_customer_reference) {
        $lead = ConnectionApplication::find($id);
        $url = config('fastconnect.root_url') . config('fastconnect.submitted_water_status_lead_url').'/'.$fast_connect_customer_reference;
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => $authorization = 'Bearer ' . $this->accessToken
        ])->get($url);



        $body = json_decode($response->body(), true);

        \Log::debug("Water Status response data", [
            'response_body' => $body,
            'success' => $response->successful(),
            'application_id' => $id,
            'status' => $response->status(),
        ]);

        $status = data_get($body, 'products.0.status');
        if ($status) {
            $this->updateWaterStatus($id, $status);
        } else {
            throw new \Exception("Water Status fetching failed");
        }

    }

    public function updateWaterStatus($id, $status)
    {
        $statusAssoc = UpdatedWaterStatus::mapFromFCStatus($status);
        if ($statusAssoc) {
            UpdatedWaterStatus::updateStatus($id, $statusAssoc['status'], $statusAssoc['reason']);
        }
    }
}
