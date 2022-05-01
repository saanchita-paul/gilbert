<?php

namespace App\Console\Commands;

use App\Modules\FastConnect\Services\UpdateWaterLeadsStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Command;

class FetchSubmitterWaterLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:submitted-water-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching submitted water list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $this->kaka2();
//        dd(0);
        UpdateWaterLeadsStatus::run();

        return 0;
    }

    private function kaka()
    {
        $client = new Client();
        $ids = [['id' => 1], ['id' => 2], ['id' => 0]];

        $requests = function ($ids) {
            foreach ($ids as $id) {
                $uri = 'jsonplaceholder.typicode.com/posts/' . $id['id'];
                yield new Request('GET', $uri);
            }
        };

        $handleSuccess = function (Response $response, $index) {
            dump('yoyo', $index);
        };
        $handleRejection = function (RequestException $exception, $index) use ($ids) {
            dump('yoyo fuck', $exception->getMessage(), ['index' => $ids[$index]]);
        };
        $pool = new Pool($client, $requests($ids), [
            'concurrency' => 50,
            'fulfilled' => fn(Response $response, $index) => $this->success($response, $index),
            'rejected' => $handleRejection,
        ]);

// Initiate the transfers and create a promise
        $promise = $pool->promise();

// Force the pool of requests to complete.
        $ress = $promise->wait(unwrap: true);
    }

    public function success(Response $response, $index): void
    {
//        dump($index);
//        dump('yoyo', $index, data_get(json_decode($response->getBody()->getContents()), 'title'));
    }

    private function kaka2()
    {
        $r = 10;
        while ($r--) {
            $this->line("kaka2: $r");
            sleep(2);
        }
    }
}
