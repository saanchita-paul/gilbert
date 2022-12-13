<?php

namespace App\Services\hubspot;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class SyncPropertiesService
{
    private array $properties;
    private array $results;
    private ProgressBar $progressBar;
    private ConsoleOutput $output;


    /**
     * @throws Exception
     */
    public function run(string $path): void
    {
        $this->properties = $this->getPropertiesFromJsonFile($path);

        $this->output = new ConsoleOutput();
        $this->progressBar = $this->createProgressBar(sizeof($this->properties));

        $this->updateConcurrently();
    }

    private function updateConcurrently(): void
    {
        $client = $this->getClient();


        $requests = function (array $properties) {
            $url = $this->getURL();

            foreach ($properties as $index => $property) {
                $fullUrl = $url . "/{$property['name']}";
                yield new Request('patch', $fullUrl, [], json_encode($property));
            }
        };

//        dd('done', $requests($this->properties));

        $pool = new Pool($client, $requests($this->properties), [
            'concurrency' => 20,
            'fulfilled' => fn(Response $response, $index) => $this->handleSuccessUpdate($response, $index),
            'rejected' => fn(RequestException $response, $index) => $this->handleFailedUpdate($response, $index),
        ]);
        $this->progressBar->start();

        $pool->promise()->wait();
        $this->progressBar->finish();
        $this->output->writeln('');

        info("RESULT", $this->results);
    }

    private function handleSuccessUpdate(Response $response, $index): void
    {
        $property = $this->properties[$index] ?? null;
        if ($property) {
            $this->results[$property['name']] = ['status' => 'updated'];
            $this->progressBar->advance();
        }
    }

    private function handleFailedUpdate(RequestException $exception, $index): void
    {
        if ($exception->getResponse()->getStatusCode() === 404) {
            $this->createProperty($index);
            return;
        }

        $property = $this->properties[$index] ?? null;
        if ($property) {
            $this->results[$property['name']] = [
                'status' => 'failed',
                'reason' => $exception->getMessage(),
            ];
            $this->progressBar->advance();
        }
    }

    private function createProperty($index): void
    {
        $property = $this->properties[$index] ?? null;
        if ($property) {
            $client = $this->getClient();
            $uri = $this->getURL();
            try {
                $response = $client->post($uri, ['json' => $property]);
                $this->results[$property['name']] = ['status' => 'created'];

            } catch (GuzzleException $e) {
                $this->results[$property['name']] = [
                    'status' => 'failed',
                    'reason' => $e->getMessage(),
                ];
            }
            $this->progressBar->advance();
        }
    }



    /**
     * @throws Exception
     */
    private function getPropertiesFromJsonFile(string $path): array
    {
        try {
            $string = file_get_contents($path);
            return json_decode($string, true);
        } catch (Exception $exception) {
            throw new Exception("Failed read properties from json: {$exception->getMessage()}");
        }
    }

    private function getClient(): Client
    {
        return new Client(['headers' => [
            'Authorization' => config('hub_spot.oauth_token'),
            'Content-Type' => 'application/json',
        ]]);
    }

    private function getURL(): string
    {
        return config('hub_spot.properties_url') . '/contacts';
    }

    private function createProgressBar($count = 0): ProgressBar
    {
        $p = new ProgressBar($this->output, $count);
        #phpcs:ignore
        $p->setFormat('<comment>%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%  %memory:6s% </comment>');

        return $p;
    }
}
