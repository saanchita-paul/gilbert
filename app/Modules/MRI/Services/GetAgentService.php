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
use App\Models\MriProperty;

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
     * @var ?int|null
     */
    private ?int $officeId;

    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    /**
     * DEFAULT GET ALL DATA WITHOUT AFTER DATE
     */
    public function __construct()
    {
        $this->setURL();
        // $this->setAfterDate(Carbon::now()->format('Y-m-d'));
        $this->exceptionHandler = new HandleExceptionService(self::class);
    }

    public function setAfterDate(string $date)
    {
        $this->afterDate = Carbon::parse($date)->format('Y-m-d');
        return $this;
    }

    public function setOfficeId(int $officeId)
    {
        $this->officeId = $officeId;
    }

    private function setToken(string $token)
    {
        $this->accessToken = $token;
        return $this;
    }

    private function setURL()
    {
        $url = empty(config('mri.base_url')) ? 'https://uatapi.propertytree.io' : config('mri.base_url');
        $endpoint = empty(config('mri.endpoints.get_all_agents')) ? '/residentialproperty/v1/Agents' : config('mri.endpoints.get_all_agents');

        $this->url = $url . $endpoint;
        return $this;
    }

    public function run()
    {
        try {
            $mriOffices = (new GetOfficeService())->getMriOffices(true, $this->officeId ?? null);
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

        if ($this->exceptionHandler->hasExceptions()) {
            $this->exceptionHandler->run();
        }
    }

    public function fetch($token)
    {
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

        $query = [];

        if (isset($this->afterDate) && !empty($this->afterDate)) {
            $query['lastModifiedOnOrAfter'] = $this->afterDate;
        }

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

        $agentsId = array_map(function ($agentData) {
            return $agentData['id'];
        }, $agentsData);

        $existedAgentsId = MriAgent::whereIn('agent_id', $agentsId)->pluck('agent_id')->toArray();

        $agentsData = array_filter($agentsData, function ($agentData) use ($existedAgentsId) {
            return !in_array($agentData['id'], $existedAgentsId) && !$agentData['deleted'];
        });

        foreach ($agentsData as $agentData) {
            try {
                $mriAgent = new MriAgent();

                $mriAgent->agent_id = $agentData['id'];
                $mriAgent->first_name = $agentData['first_name'];
                $mriAgent->last_name = $agentData['last_name'];
                $mriAgent->email_address = $agentData['email_address'];
                $mriAgent->mobile_phone_number = $agentData['mobile_phone_number'];
                if (!empty($agentData['roles'])) {
                    $mriAgent->roles = implode(',', $agentData['roles']);
                }
                $mriAgent->is_deleted = $agentData['deleted'];
                $mriAgent->mri_office_id = $officeId;

                $mriAgent->save();
                $this->saveAgentProperty($mriAgent);
                $updatedAgentIds[] = $mriAgent->id;
            } catch (\Exception $e) {
                $this->exceptionHandler->addException($e, $agentData);
            }
        }

        if (!empty($updatedAgentIds)) {
            $successMessage = sprintf('Created %s mri agents', count($updatedAgentIds));
            info($successMessage, ['mri_agent_ids' => $updatedAgentIds]);
        }
        return $updatedAgentIds;
    }

    private function saveAgentProperty($mriAgent)
    {
        $mriProperties = MriProperty::where('agents', 'LIKE', "%$mriAgent->agent_id%")->get();
        foreach ($mriProperties as $mriProperty) {
            $mriProperty->mriAgents()->sync($mriAgent->id);
        }
    }
}
