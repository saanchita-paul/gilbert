<?php

namespace App\Services\hubspot;

use App\Models\ClickConversion;
use App\Models\ConnectionApplication;
use App\Services\GoogleAds\UploadClickConversionService;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;

class FetchGCLService {
    private array $mappedApplication = [];
    protected UploadClickConversionService $clickConversionService;

    public function __construct() {
        $this->clickConversionService = new UploadClickConversionService();
    }

    private function getClient(): PendingRequest
    {
        return Http::withHeaders(['Authorization' => config('hub_spot.oauth_token')]);
    }

    public function fetchConnectionApplications(): Collection
    {
        return ConnectionApplication::query()
            ->select(["hubspot_contact_id", "id"])
            ->with(['clickConversion' => function ($query){
                $query->where('is_uploaded_click', ClickConversion::IS_UPLOADED_CLICK_FALSE);
            }])
            ->where('source', ConnectionApplication::SOURCE_HOOD_LEAD)
            ->whereNotNull('hubspot_contact_id')
            ->get();
    }

    public function fetchGclId($applications): void
    {
        foreach ($applications AS $app){
            //call hubspot api and get their gcl_id
            $url = str_replace('${id}', $app->hubspot_contact_id, config('hub_spot.update_contact'));
            $response = $this->getClient()->get($url);
            $body = json_decode($response->body(), true);
            $gclID = $body['properties']['hs_google_click_id']['value'] ?? null;

            //insert click conversion record if not created yet
            if(!$app->clickConversion){
                $this->createClickConversion($app->id, $gclID);
            }

            //updating gcl_id to the click conversion table along with apps clickConversion
            $app->clickConversion = $this->updateGclID($app->id, [
                "gcl_id" => $gclID,
                "last_checked" => Carbon::now(),
            ]);

            $this->mappedApplication[] = $app;
        }
    }

    public function uploadClickConversion(): void
    {
        foreach ($this->mappedApplication AS $app){
            $gclId = $app->clickConversion->gcl_id;

            if(!$gclId)
            {
                continue;
            }

            $response = $this->clickConversionService->uploadClick($gclId);

            // if data has been sent to google analytics successfully
            if($response)
            {
                $this->uploadedClickSuccessful($app->id);
            }
        }
    }

    public function uploadedClickSuccessful($appID): void
    {
        ClickConversion::where('connection_application_id', $appID)->update([
            "is_uploaded_click" => ClickConversion::IS_UPLOADED_CLICK_TRUE
        ]);
    }

    public function updateGclID($appID, $data)
    {
        return ClickConversion::where('connection_application_id', $appID)->update($data);
    }

    public function createClickConversion($appID, $gclID): void
    {
        ClickConversion::create([
           "connection_application_id" => $appID,
            "gcl_id" => $gclID,
            "last_checked" => Carbon::now()
        ]);
    }

}
