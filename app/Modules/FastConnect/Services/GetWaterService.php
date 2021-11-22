<?php


namespace App\Modules\FastConnect\Services;


use App\Jobs\GetWaterProcessingJob;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GetWaterService
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


        $processingLeads = DB::table('connection_services AS CS')
            ->join('connection_applications AS CA','CA.id', '=', 'CS.connection_application_id')
            ->where('CS.service_type','=', 'water')
            ->where('CS.status','=', ConnectionService::WATER_STATUS_IN_PROGRESS)
            ->get(['CA.id', 'CA.fast_connect_customer_reference']);

        info('data', $processingLeads->toArray());



        foreach ($processingLeads as $lead) {

            if(!is_null($lead->fast_connect_customer_reference)) {
                GetWaterProcessingJob::dispatch($lead->id, $lead->fast_connect_customer_reference);
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


        $products = (json_decode($response->body()))->products;

        info(json_encode($response->body()));
        $status = ($products[0])->status;
        info($status);

        $this->updateWaterStatus($id, $status);

    }

    public function updateWaterStatus($id, $status)
    {
        $connectioService = ConnectionService::query()->where('connection_application_id', $id)->first();

        switch ($status) {
            case 'CANCELLED':
                $connectioService->status = ConnectionService::WATER_STATUS_CANT_CONNECT;
                break;
            case 'COMPLETE':
                $connectioService->status = ConnectionService::WATER_STATUS_CONNECTED;
                break;

            case 'CONFIRMED':
                $connectioService->status = ConnectionService::WATER_STATUS_CONNECTED;
                break;

            case 'DECLINED':
                $connectioService->status = ConnectionService::WATER_STATUS_CANT_CONNECT;
                break;

            default:
                break;

        }
        $connectioService->update();


    }


}
