<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use App\Models\MriAgent;
use App\Models\MriApplication;
use App\Models\MriOffice;
use App\Models\MriProperty;
use App\Models\MriAgentProperty;


class GetPropertyService 
{
    const MANAGEMENT_TYPE = 'Residential';

    /**
     * @var string|null
     */
    private ?string $accessToken;

    /**
     * @var string|null
     */
    private ?string $url;

    /**
     * @var HandleExceptionService
     */
    private HandleExceptionService $exceptionHandler;

    public function __construct()
    {
        $this->exceptionHandler = new HandleExceptionService(self::class);
    }

    private function setToken(string $token)
    {
        $this->accessToken = $token;
        return $this;
    }

    private function setURL($id)
    {
        $this->url = config('mri.base_url') . config('mri.endpoints.get_property_by_id') . $id;
        return $this;
    }

    public function run()
    {
        $mriOffices = MriOffice::get();
        foreach ($mriOffices as $office){
            $token = $office->key;
            $this->setToken($token);

            $client = new Client([
                'headers' => [
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => 'Bearer ' . $this->accessToken
                ],
            ]);

            $mriApps = MriApplication::doesntHave('propertyDetail')->where('mri_office_id', $office->id)->get();
            $savedPropertyIds = [];
    
            foreach ($mriApps as $app) {
                try {
                    if (empty($app->property)) {
                        throw new \Exception('Missing property id for mri application id '.$app->id);
                    }
    
                    $this->setURL($app->property);
                    $response = $client->request('GET', $this->url);
            
                    $data = json_decode($response->getBody()->getContents(), true);
                    
                    $savedPropertyId = $this->saveProperty($app->id, $data);
                    $savedPropertyIds[] = $savedPropertyId;
                } catch (RequestException $e) {
                    $this->exceptionHandler->addException($e, ['mri_app_id' => $app->id]);
                } catch (\Exception $e) {
                    $this->exceptionHandler->addException($e, ['mri_app_id' => $app->id]);
                }
            }

            if (!empty($savedPropertyIds)){
                $message = sprintf('Updated %s mri properties', count($savedPropertyIds));
                dump($message);
                info($message, ['mri_property_ids' => $savedPropertyIds]);
            }
            
            if ($this->exceptionHandler->hasExceptions()){
                $this->exceptionHandler->run();
            }
        }
    }

    private function saveProperty($mri_app_id, $propertiesData)
    {
        try {
            $filtered = array_filter($propertiesData, function($value){
                return !$value['deleted'] && !$value['archived'];
            });
            
            if (count($filtered) == 0) {
                \Log::error('No property data from response that is not deleted/archived', $propertiesData);
                throw new \Exception("No valid property data provided for mri application id '.$mri_app_id");
            }
            
            if (count($filtered) > 1) {
                \Log::error('More than one property data that is not deleted/archived', $propertiesData);
                throw new \Exception("More than one property data provided for mri application id '.$mri_app_id");
            }
    
            $propertiesData = $filtered;
            
            $property = $propertiesData[0];
    
            $mriProperty = MriProperty::where('mri_application_id', $mri_app_id)->first();
            if (!$mriProperty){
                $mriProperty = new MriProperty();
                $mriProperty->mri_application_id = $mri_app_id;
            }
    
            $mriProperty->address_line_1 = $property['address']['address_line_1'];
            $mriProperty->address_line_2 = $property['address']['address_line_2'];
            $mriProperty->suburb = $property['address']['suburb'];
            $mriProperty->state = $property['address']['state'];
            $mriProperty->post_code = $property['address']['post_code'];
            $mriProperty->country = $property['address']['country'];
            $mriProperty->unit = $property['address']['unit'];
            $mriProperty->is_deleted = $property['deleted'];
            $mriProperty->is_archived = $property['archived'];
            
            $mriProperty->save();
            
            if (!empty($property['agents'])) 
                $mriProperty = $this->saveAgentProperty($mriProperty, $property['agents']);
    
            return $mriProperty->id;
        } catch (\Exception $e) {
            $data = [
                'mri_app_id' => $mri_app_id,
                'propertiesData' => $propertiesData
            ];
            $this->exceptionHandler->addException($e, $data);
        }
    }

    private function saveAgentProperty(MriProperty $mriProperty, array $propertyAgents)
    {
        $mriProperty->agents = implode(',', $propertyAgents);
        $mriProperty->save();

        $mriAgentIds = MriAgent::whereIn('agent_id', $propertyAgents)->pluck('id')->toArray(); 
        $mriProperty->mriAgents()->sync($mriAgentIds);

        return $mriProperty;
    }
}