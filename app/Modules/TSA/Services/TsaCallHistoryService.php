<?php

namespace TSA\Services;

use Carbon\Carbon;
use App\Models\APILog;
use App\Models\TSACallHistory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use Exception;

/**
 *
 */
class TsaCallHistoryService
{
    /**
     * @var array
     */
    private array $appForUpdate = [];

    /**
     * @var array
     */
    private array $newAttempts = [];

    /**
     * @var array
     */
    private array $failedAppIds = [];

    /**
     * @var int
     */
    private int $concurrency = 100;
    /**
     * @var int
     */
    private int $currentChunk = 0;
    /**
     * @var int
     */
    private int $chunkSize = 500;
    /**
     * @var int
     */
    private int $totalChunks = 0;

    /**
     *
     */
    public function __construct()
    {
        $this->fetchTotalChunk();
    }

    /**
     * @return $this
     */
    private function fetchConcurrently(): static
    {
        dump(sizeof($this->appForUpdate) . ' apps to update');
        $url = \config('tsa.root_url') . \config('tsa.call_history');
        $client = new Client(['headers' => [
            'content-type' => 'application/json',
            'X-API-Service' => \config('tsa.x_api_service_name'),
            'X-API-Token' => \config('tsa.x_api_token')
        ]]);

        $requests = function ($apps) use ($url) {
            foreach ($apps as $app) {
                yield new Request('GET', $url . $app['tsa_lead_id']);
            }
        };
        $pool = new Pool($client, $requests($this->appForUpdate), [
            'concurrency' => $this->concurrency,
            'fulfilled' => fn(Response $response, $index) => $this->handleSuccess($response, $index),
            'rejected' => fn(Exception $e, $index) => $this->handleError($e, $index),
        ]);

        $pool->promise()->wait();

        return $this;
    }

    /**
     * @param Response $response
     * @param $index
     * @return void
     */
    private function handleSuccess(Response $response, $index): void
    {
        $body = json_decode($response->getBody()->getContents(), true);
        $app = $this->appForUpdate[$index] ?? null;

        if ($app) {
            $this->saveCallHistory($app['id'], $body);
        } else {
            Log::error('No app found for id: ' . $index);
        }
    }


    /**
     * @param RequestException $exception
     * @param $index
     * @return void
     */
    private function handleError(Exception $exception, $index): void
    {
        $app = $this->appForUpdate[$index] ?? null;
        if ($app) {
            $id = $app['id'];
            $this->failedAppIds[$id] = $exception->getMessage();
            \Log::error("ERROR ID: $id", [$exception->getMessage()]);
        }
    }


    /**
     * @param $connection_application
     * @return false|string
     */
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

    /**
     * @param int $appId
     * @param array $callHistory
     * @return void
     */
    public function saveCallHistory(int $appId, array $callHistory): void
    {
        $attemps = $callHistory['attempts'];

//        dump(sizeof($attemps));

        $ids = collect($attemps)->pluck('attempt_id')->toArray();

        $foundAttempts = TSACallHistory::query()
            ->select(['attempt_id'])
            ->where('connection_application_id', $appId)
            ->whereIn('attempt_id', $ids)
            ->get()
            ->pluck('attempt_id')
            ->toArray();

        try {
            foreach ($attemps as $value) {
                if (!in_array(data_get($value, 'attempt_id'), $foundAttempts)) {
//                if (true) {

                    $this->newAttempts[] = [
                        'connection_application_id' => $appId,
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

//            TSACallHistory::query()->insert($newAttempts);

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            dump($exception->getMessage());
        }

    }

    /**
     * @return $this
     */
    public function loadApp(): static
    {
        $t = $this->getAppBuilder()
            ->skip($this->currentChunk * $this->chunkSize)
            ->take($this->chunkSize)
            ->get();

        dump("F -> " . $t->first()->id . " <> L -> " .  $t->last()->id);

        $this->appForUpdate = $t->toArray();

        return $this;
    }

    /**
     * @return Builder
     */
    private function getAppBuilder(): Builder
    {
        return ConnectionApplication::query()
            ->select(['id', 'tsa_lead_id'])
//            ->whereIn('id', [18329, 19240, 19242]) // for testing only
            ->whereNotNull('tsa_lead_id')
            ->whereNotIn('status', [
                ConnectionApplication::STATUS_CLOSED,
                ConnectionApplication::STATUS_REJECTED,
                ConnectionApplication::STATUS_ESCALATED,
                ConnectionApplication::STATUS_SUBMITTED,
            ]);
    }

    /**
     *
     * @return void
     */
    private function fetchTotalChunk(): void
    {
        $total = $this->getAppBuilder()->count();

        $this->totalChunks = ceil($total / $this->chunkSize);
    }

    /**
     * @return void
     */
    private function saveNewAttempts(): void
    {
        foreach (array_chunk($this->newAttempts, 500) as $chunk) {
            TSACallHistory::query()->insert($chunk);
        }
    }

    /**
     * @return void
     */
    public function start(): void
    {
//        $this->loadApp()->fetchConcurrently()->saveNewAttempts();

        while ($this->currentChunk < $this->totalChunks) {
            $this->newAttempts= [];
            $this->loadApp()->fetchConcurrently()->saveNewAttempts();
            dump("done: " . $this->currentChunk + 1);
            $this->currentChunk++;
        }

        dump("Failed Jobs: ", sizeof($this->failedAppIds));
    }

    /**
     * Alias to run start method directly
     *
     * @return void
     */
    public static function run(): void
    {
        (new static())->start();
    }


}
