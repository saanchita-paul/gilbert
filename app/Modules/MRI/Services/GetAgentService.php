<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use App\Models\MriAgent;
use App\Models\MriOffice;

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

    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    public function __construct()
    {
        $this->setURL();
        $this->setAfterDate(Carbon::now()->format('Y-m-d'));
        $this->exceptionHandler = new HandleExceptionService(self::class);
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
        try {
            $mriOffices = MriOffice::get();
            foreach ($mriOffices as $office) {
                $token = $office->key;
                $response = $this->fetch($token);
                $data = json_decode($response->getBody()->getContents(), true);
                $this->saveAgent($office->id, $data);
            }
        } catch (RequestException $e) {
            $this->exceptionHandler->addException($e);
        } catch (\Exception $e) {
            $this->exceptionHandler->addException($e);
        }

        if ($this->exceptionHandler->hasExceptions()){
            $this->exceptionHandler->run();
        }
    }

    public function fetch ($token) {
        $token = $token;
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

        return $client->request('GET', $this->url, $options);
    }

    /**
     * @param int officeId
     * @param array agentsData
     */
    private function saveAgent($officeId, $agentsData)
    {
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
            } catch (\Exception $e) {
                $this->exceptionHandler->addException($e, $agentData);
            }
            
        }
        
        if (!empty($updatedAgentIds)){
            $successMessage = sprintf('Updated %s mri agents', count($updatedAgentIds));
            dump($successMessage);
            info($successMessage, ['mri_agent_ids' => $updatedAgentIds]);
        }
        return $updatedAgentIds;
    }
}