<?php


namespace App\Modules\FastConnect\Services;


use App\Jobs\WaterStatusUpdateJob;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Services\Agency\UpdatedWaterStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

/**
 *
 */
class UpdateWaterLeadsStatus
{
    /**
     * @var string|null
     */
    private ?string $accessToken;
    /**
     * @var array
     */
    private array $leads = [];
    /**
     * @var int
     */
    private int $totalChunks;
    /**
     * @var int
     */
    private int $chunkSize = 500;
    /**
     * @var int
     */
    private int $concurrency = 100;
    /**
     * @var int
     */
    private int $currentChunk = 0;

    /**
     * @var array
     */
    private array $failedLeadIds = [];
    /**
     * @var false|mixed
     */
    private mixed $runAll;


    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->runAll = $options['all'] ?? false;

        $this->fetchTotalChunk();
        $this->authenticate();
    }

    /**
     * @param array $options
     * @return void
     */
    public static function run(array $options = []): void
    {
        $self = new static($options);
        $self->start();
    }

    /**
     * @return void
     */
    public function start(): void
    {
        while ($this->currentChunk <= $this->totalChunks) {
            $this->fetchLeads();
            dump(collect($this->leads)->pluck('id')->toArray());
            $this->updateStatusConcurrently();
            $this->currentChunk++;
        }

        dump($this->failedLeadIds);
    }

    /**
     * @return void
     */
    private function fetchTotalChunk(): void
    {
        $total = $this->getLeadsBuilder()->count();
        $this->totalChunks = ceil($total / $this->chunkSize);
    }

    /**
     * @return Builder
     */
    private function getLeadsBuilder(): Builder
    {
        if ($this->runAll) {
            $statuses = [];
        } else {
            $statuses = ['WATER_STATUS_CONNECTED'];
        }
        return ConnectionApplication::query()
            ->select(['id', 'fast_connect_customer_reference'])
            ->whereHas('connectionServices', function (Builder $query) use ($statuses) {
                $query->whereNotIn('status', $statuses)
                    ->where('service_type', ConnectionService::TYPE_WATER);
            })
            ->whereNotNull('fast_connect_customer_reference');
    }

    /**
     * @return void
     */
    private function fetchLeads(): void
    {
        $this->leads = $this->getLeadsBuilder()
            ->skip($this->currentChunk * $this->chunkSize)
            ->take($this->chunkSize)
            ->get()
            ->toArray();
    }


    /**
     * @return void
     */
    private function updateStatusConcurrently(): void
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->accessToken
            ],
        ]);
        $requests = function ($leads) {
            foreach ($leads as $lead) {
                yield new Request('GET', $this->getURL($lead['fast_connect_customer_reference']));
            }
        };

        $pool = new Pool($client, $requests($this->leads), [
            'concurrency' => $this->concurrency,
            'fulfilled' => fn(Response $response, $index) => $this->handleSuccess($response, $index),
            'rejected' => fn(RequestException $e, $index) => $this->handleError($e, $index),
        ]);

        $pool->promise()->wait();
    }


    /**
     * @param string $customerReference
     * @return string
     */
    private function getURL(string $customerReference): string
    {
        return config('fastconnect.root_url')
            . config('fastconnect.submitted_water_status_lead_url')
            . '/'
            . $customerReference;
    }

    /**
     * @param RequestException $e
     * @param $index
     * @return void
     */
    private function handleError(RequestException $e, $index): void
    {
        $leadId = $this->leads[$index]['id'] ?? null;
        $this->failedLeadIds[$leadId] = $e->getMessage();
        \Log::error("ERROR ID: {$leadId}", [$e->getMessage()]);
    }

    /**
     * @param Response $response
     * @param $index
     * @return void
     */
    private function handleSuccess(Response $response, $index): void
    {
        $data = json_decode($response->getBody()->getContents(), true);
        $status = $this->getParsedStatus($data);
        $leadId = $this->leads[$index]['id'] ?? null;
        dump("SUCCESS ID: {$leadId}");

        if ($leadId) {
            $this->updateWaterStatus($leadId, $status);
        } else {
            \Log::error("fuck");
        }
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function getParsedStatus(array $data): ?string
    {
        #todo: HCO-808 -> handle multiple statuses
        return data_get($data, 'products.0.status');
    }


    /**
     * @param int $id
     * @param string|null $status
     */
    public function updateWaterStatus(int $id, ?string $status): void
    {
        $statusAssoc = UpdatedWaterStatus::mapFromFCStatus($status);
        if ($statusAssoc) {
            UpdatedWaterStatus::updateStatus($id, $statusAssoc['status'], $statusAssoc['reason']);
        }
    }

    /**
     * @return $this
     */
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

    /**
     * @deprecated
     * @return void
     */
    public function getAllSubmittedWaterLead() {
        $leads = ConnectionService::query()
            ->with('connectionApplication')
            ->where('service_type', 'water')
            ->whereHas('connectionApplication', function (Builder $lead) {
                $lead->whereNotNull('fast_connect_customer_reference');
            })
            ->where('status', '!=',  ConnectionService::WATER_STATUS_CONNECTED)
            ->get();
        info("[GetWaterService:getAllSubmittedWaterLead] Water leads", $leads->pluck('connection_application_id')->toArray());



        foreach ($leads as $lead) {

            if(!is_null($lead->connectionApplication?->fast_connect_customer_reference)) {
                WaterStatusUpdateJob::dispatch($lead->connection_application_id, $lead->connectionApplication->fast_connect_customer_reference);
            }

        }

    }

    /**
     * @Deprecated
     */
    public function getSubmittedDetails($id, $fast_connect_customer_reference)
    {
        $url = config('fastconnect.root_url')
            . config('fastconnect.submitted_water_status_lead_url')
            .'/'.$fast_connect_customer_reference;

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . $this->accessToken
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


}
