<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use App\Models\MriApplication;
use App\Models\MriOffice;

use Illuminate\Support\Carbon;

class GetTenanciesService 
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
        $endpoint = empty(config('mri.endpoints.get_tenancies')) ? '/residentialproperty/v1/Tenancies' : config('mri.endpoints.get_tenancies');
        
        $this->url = $url . $endpoint;
        return $this;
    }

    public function run()
    {
        try {
            if (isset($this->officeId) && !empty($this->officeId)) {
                $mriOffices = MriOffice::where('office_id', $this->officeId)->get();
                if (count($mriOffices) == 0)
                    throw new \Exception('Unable to find MRI office with Gilbert office id = ' . $this->officeId);
            }
            else {
                $mriOffices = MriOffice::get();
            }
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
        
                $query = [
                    'managementType' => self::MANAGEMENT_TYPE,
                ];

                if (isset($this->afterDate) && !empty($this->afterDate)){
                    $query['lastModifiedOnOrAfter'] = $this->afterDate;
                }
        
                $options = [
                    'query' => $query
                ];
        
                $response = $client->request('GET', $this->url, $options);
        
                $data = json_decode($response->getBody()->getContents(), true);          
                
                $this->saveTenancies($office->id, $data);
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

    /**
     * @param int officeId
     * @param array tenanciesData
     * 
     * @return array exceptions
     */
    public function saveTenancies($officeId, $tenanciesData)
    {
        $updatedTenancyIds = []; 

        $tenanciesId = array_map(function($tenancy){
            return $tenancy['id'];
        }, $tenanciesData);

        $existedTenanciesId = MriApplication::whereIn('tenancy_id', $tenanciesId)->pluck('tenancy_id')->toArray();

        $tenanciesData = array_filter($tenanciesData, function($tenancy) use ($existedTenanciesId){
            return !in_array($tenancy['id'], $existedTenanciesId) && !$tenancy['deleted'] && !$tenancy['archived']; 
        });

        foreach ($tenanciesData as $tenancy) {
            try {
                $mriApp = new MriApplication();

                $mriApp->mri_office_id = $officeId;
                $mriApp->tenancy_id = $tenancy['id'];
                $mriApp->name = $tenancy['name'];
                $mriApp->property = $tenancy['property'];
                $mriApp->rent_amount = $tenancy['rent']['amount'] ?? null;
                $mriApp->rent_period = $tenancy['rent']['period'] ?? null;
                $mriApp->prospect = $tenancy['prospect'];
                $mriApp->lease_detail_charge_tenants_water_usage = $tenancy['lease_detail']['charge_tenants_water_usage'] ?? null;

                if (!empty($tenancy['lease_start_date']))
                    $mriApp->lease_start_date = Carbon::parse($tenancy['lease_start_date']);
                if (!empty($tenancy['lease_end_date']))
                    $mriApp->lease_end_date = Carbon::parse($tenancy['lease_end_date']);
                if (!empty($tenancy['original_lease_start_date']))
                    $mriApp->original_lease_start_date = Carbon::parse($tenancy['original_lease_start_date']);
                if (!empty($tenancy['vacate_date']))
                    $mriApp->vacate_date = Carbon::parse($tenancy['vacate_date']);
                
                foreach ($tenancy['contacts'] as $contact){
                    if (!empty($mriApp->authorized_first_name) && !empty($mriApp->first_name)) {
                        break;
                    }

                    if (count($tenancy['contacts']) == 1 || $contact['is_primary']){
                        $mriApp->title = $contact['title'];
                        $mriApp->first_name = $contact['first_name'];
                        $mriApp->last_name = $contact['last_name'];
                        $mriApp->email_address = $contact['email_address'];
                        $mriApp->mobile_phone_number = $contact['mobile_phone_number'];
                        $mriApp->home_number = $contact['phone_number'];
                        $mriApp->is_marketing = !$contact['no_marketing'];
                    }
                    else {
                        $mriApp->authorized_title = $contact['title'];
                        $mriApp->authorized_first_name = $contact['first_name'];
                        $mriApp->authorized_last_name = $contact['last_name'];
                        $mriApp->authorized_email_address = $contact['email_address'];
                        $mriApp->authorized_mobile_phone_number = $contact['mobile_phone_number'];
                        $mriApp->authorized_home_number = $contact['phone_number'];
                    }
                }
                
                $mriApp->is_deleted = $tenancy['deleted'];
                $mriApp->is_archived = $tenancy['archived'];

                $mriApp->save();
                $updatedTenancyIds[] = $mriApp->id;
            } catch (\Exception $e) {
                $data = [
                    'officeId' => $officeId,
                    'tenancy' => $tenancy,
                ];
                $this->exceptionHandler->addException($e, $data);
            }
        }

        if (!empty($updatedTenancyIds)){
            $message = sprintf('Created %s mri applications', count($updatedTenancyIds));
            // dump($message);
            info($message, ['mri_application_ids' => $updatedTenancyIds]);
        }

        return $updatedTenancyIds;
    }
}