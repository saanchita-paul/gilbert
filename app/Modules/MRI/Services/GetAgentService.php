<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use App\Models\MriAgent;
use App\Models\MriOffice;

class GetAgentService 
{
    /**
     * @var string|null
     */
    private ?string $accessToken;

    /**
     * @var string|null
     */
    private ?string $url;

    public function __construct()
    {
        $this->getURL();
        $this->authenticate();
    }

    private function authenticate()
    {        
        // TODO: get key from mri_offices table
        $this->accessToken = '4e1df42e-5c53-4762-b07a-79f8d731e0bc:4e477c8d-21b5-42ff-a03c-294b41246889';
        return $this;
    }

    private function getURL()
    {
        $this->url = config('mri.base_url') . config('mri.endpoints.get_all_agents');
        return $this;
    }

    public function run()
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->accessToken
            ],
        ]);

        $query = [
            'lastModifiedOnOrAfter' => '2022-05-01'
        ];

        $options = [
            'query' => $query
        ];

        $response = $client->request('GET', $this->url, $options);

        $data = json_decode($response->getBody()->getContents(), true);

        // TODO: filter by mri office id
        $existingAgentIds = MriAgent::pluck('agent_id')->toArray();

        $filteredData = array_filter($data, function($value) use ($existingAgentIds){
            return !in_array($value['id'], $existingAgentIds);
        });

        return $filteredData;
    }
}