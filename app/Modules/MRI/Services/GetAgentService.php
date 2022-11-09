<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use App\Models\MriAgent;
use App\Models\MriOffice;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

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

    /**
     * @var string|null
     */
    private ?string $afterDate;

    public function __construct()
    {
        $this->setURL();
        $this->setAfterDate(Carbon::now()->format('Y-m-d'));
    }

    public function setAfterDate(string $date)
    {
        $this->afterDate = Carbon::parse($date)->format('Y-m-d');
        return $this;
    }

    private function setToken(string $token)
    {
        $this->accessToken = $token;
        return $this;
    }

    private function setURL()
    {
        $this->url = config('mri.base_url') . config('mri.endpoints.get_all_agents');
        return $this;
    }

    public function run()
    {
        $mriOffices = MriOffice::get();
        foreach ($mriOffices as $office) {
            $token = $office->key;
            $this->setToken($token);
            $headers = [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->accessToken
            ];

            $client = new Client([
                'headers' => $headers,
            ]);
            
            $query = [
                'lastModifiedOnOrAfter' => $this->afterDate
            ];

            $options = [
                'query' => $query
            ];

            $response = $client->request('GET', $this->url, $options);

            // TODO: handle request exception
            $data = json_decode($response->getBody()->getContents(), true);

            $savedAgentIds = $this->saveAgent($office->id, $data);

            if (!empty($savedAgentIds)){
                $message = sprintf('Updated %s mri agents', count($savedAgentIds));
                dump($message);
                info($message, ['mri_agent_ids' => $savedAgentIds]);
                // TODO: send email?
            }
        }
    }

    /**
     * @param int officeId
     * @param array agentsData
     * 
     * @return array exceptions
     */
    private function saveAgent($officeId, $agentsData)
    {
        $exceptionArray = [];
        $updatedAgentIds = [];

        foreach ($agentsData as $agentData) {
            try {
                $mriAgent = MriAgent::where('agent_id', $agentData['id'])->first();
                if (!$mriAgent)
                    $mriAgent = new MriAgent();
                
                $mriAgent->agent_id = $agentData['id'];
                $mriAgent->first_name = $agentData['first_name'];
                $mriAgent->last_name = $agentData['last_name'];
                $mriAgent->email_address = $agentData['email_address'];
                $mriAgent->mobile_phone_number = $agentData['mobile_phone_number'];
                if (!empty($agentData['roles'])) 
                    $mriAgent->roles = implode(',', $agentData['roles']);
                $mriAgent->is_deleted = $agentData['deleted'];
                $mriAgent->mri_office_id = $officeId;
    
                $mriAgent->save();
                $updatedAgentIds[] = $mriAgent->id;
            } catch (\Exception $exception) {
                $exceptionArray[] = [
                    'exception' => $exception,
                    'data' => $agentData,
                ];
            }     
        }

        if (!empty($exceptionArray)){
            // TODO: handle save exception
        }

        return $updatedAgentIds;
    }
}