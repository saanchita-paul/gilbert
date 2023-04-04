<?php

namespace App\Services\hubspot;

use App\Models\ClickConversion;
use App\Models\ConnectionApplication;
use App\Services\GoogleAds\UploadClickConversionService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;

class FetchGCLService {
    protected array $gclIDList = [];
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
        foreach ($applications AS $app)
        {
            //call hubspot api and get their gcl_id
            $url = str_replace('${id}', $app->hubspot_contact_id, config('hub_spot.update_contact'));
            $response = $this->getClient()->get($url);
            $body = json_decode($response->body(), true);
            $gclID = $this->parseGclID($body['properties']);

            //insert click conversion record if not created yet
            if(!$app->clickConversion && $gclID)
            {
                $this->createClickConversion($app->id, $gclID);
            }
            else if($gclID)
            {
                //updating gcl_id to the click conversion table along with apps clickConversion if existed in click conversion table
                $this->updateGclID(
                    $app->id,
                    [
                        "gcl_id" => $gclID,
                        "last_checked" => Carbon::now(),
                    ]
                );
            }
        }
        info("gclList", $this->gclIDList);
    }
    public function fetchConcurrentGclId($applications): void
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => config('hub_spot.oauth_token')
            ],
        ]);

        $requests = function ($applications) {
            foreach ($applications as $app) {
                $url = str_replace('${id}', $app->hubspot_contact_id, config('hub_spot.update_contact'));
                yield new Request('GET',  $url);
            }
        };

        $pool = new Pool($client, $requests($applications), [
            'concurrency' => 10,
            'fulfilled' => fn(Response $response, $index) => $this->handleSuccess(
                $response,
                $applications[$index]->id,
                $applications[$index]->clickConversion
            ),
            'rejected' => fn(RequestException $e, $index) => $this->handleError($e, $applications[$index]->id),
        ]);

        $pool->promise()->wait();
    }

    private function handleSuccess(Response $response, $appID, $clickConversion): void
    {
        $data = json_decode($response->getBody()->getContents(), true);
        $gclID = $this->parseGclID($data['properties']);

        //insert click conversion record if not created yet
        if(!$clickConversion && $gclID)
        {

            $this->createClickConversion($appID, $gclID);
        }
        else if($gclID)
        {
            //updating gcl_id to the click conversion table along with apps clickConversion if existed in click conversion table
            $this->updateGclID(
                $appID,
                [
                    "gcl_id" => $gclID,
                    "last_checked" => Carbon::now(),
                    "generated_from_creation" => ClickConversion::GENERATED_FROM_CREATION_FALSE
                ]
            );
        }
    }

    private function handleError(RequestException $e, $index): void
    {
        \Log::error("Guzzle error {$index}", [$e->getMessage()]);
    }

    public function uploadClickConversion(): void
    {
        foreach ($this->gclIDList AS $item){

            if(!$item->gcl_id)
            {
                continue;
            }

            $response = $this->clickConversionService->uploadClick($item->gcl_id);

            // if data has been sent to google analytics successfully
            if($response)
            {
                $this->uploadedClickSuccessful($item->app_id);
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
        $this->gclIDList[] = [
            "app_id" => $appID,
            "gcl_id" => $data['gcl_id']
        ];
        return ClickConversion::where('connection_application_id', $appID)->update($data);
    }

    public function createClickConversion($appID, $gclID): void
    {
        ClickConversion::create([
           "connection_application_id" => $appID,
            "gcl_id" => $gclID,
            "last_checked" => Carbon::now(),
            "generated_from_creation" => ClickConversion::GENERATED_FROM_CREATION_FALSE
        ]);
    }

    public function parseGclID($response)
    {
        if(array_key_exists('hs_google_click_id', $response))
        {
            return $response['hs_google_click_id']['value'];
        }
        return null;
    }

}
