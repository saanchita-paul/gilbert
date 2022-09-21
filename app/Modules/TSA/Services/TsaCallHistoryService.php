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
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

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
    private int $concurrency;
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

    private ProgressBar $progressBar;

    private ConsoleOutput $consoleOutput;

    private int $total;

    /**
     *
     */
    public function __construct(int $concurrency)
    {
        $this->concurrency = $concurrency;
        $this->fetchTotalChunk();

        $this->consoleOutput = new ConsoleOutput();

        $this->progressBar = $this->createProgressBar($this->total);
    }

    /**
     * @return $this
     */
    private function fetchConcurrently(): static
    {
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
        $this->progressBar->advance();
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

//         $this->consoleOutput->writeln($exception->getMessage());
        $this->progressBar->advance();
    }


    /**
     * @param int $appId
     * @param array $callHistory
     * @return void
     */
    public function saveCallHistory(int $appId, array $callHistory): void
    {
        $attempts = $callHistory['attempts'];

//        dump(sizeof($attemps));

        $ids = collect($attempts)->pluck('attempt_id')->toArray();

        $foundAttempts = TSACallHistory::query()
            ->select(['attempt_id'])
            ->where('connection_application_id', $appId)
            ->whereIn('attempt_id', $ids)
            ->get()
            ->pluck('attempt_id')
            ->toArray();

        try {
            foreach ($attempts as $value) {
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
        $this->appForUpdate = $this->getAppBuilder()
            ->skip($this->currentChunk * $this->chunkSize)
            ->take($this->chunkSize)
            ->get()
            ->toArray();

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
        $this->total = $this->getAppBuilder()->count();

        $this->totalChunks = ceil($this->total / $this->chunkSize);
    }

    /**
     * @return void
     */
    private function saveNewAttempts(): void
    {
        foreach (array_chunk($this->newAttempts, 500) as $chunk) {
            TSACallHistory::query()->insert($chunk);
        }

        $this->newAttempts= [];
    }

    /**
     * @return void
     */
    public function start(): void
    {
//        $this->loadApp()->fetchConcurrently()->saveNewAttempts();
        $this->progressBar->start();

        while ($this->currentChunk < $this->totalChunks) {
            $this->loadApp()->fetchConcurrently()->saveNewAttempts();
            $this->currentChunk++;
        }

        $this->progressBar->finish();

        $this->logResults();
    }

    /**
     * Logging result to terminal
     *
     * @return void
     */
    private function logResults(): void
    {
        $this->consoleOutput->writeln("\n");
        $this->consoleOutput->writeln("Total Jobs: " . $this->total);
        $this->consoleOutput->writeln("<info>Success Jobs: " . $this->total - sizeof($this->failedAppIds). "</info>");
        $this->consoleOutput->writeln("<error>Failed Jobs: " . sizeof($this->failedAppIds) . "</error>");
        $this->consoleOutput->writeln("\n");

    }

    /**
     * Alias to run start method directly
     *
     * @return void
     */
    public static function run(int $concurrency): void
    {
        (new static($concurrency))->start();
    }


    private function createProgressBar($count = 0): ProgressBar
    {
        $this->consoleOutput->writeln('<info>Fetching TSA call history...</info>');
        $p = new ProgressBar($this->consoleOutput, $count);
        $p->setFormat('<comment>%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%  %memory:6s%</comment>');

        return $p;
    }
}
