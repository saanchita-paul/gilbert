<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use App\Models\MriApplication;
use App\Models\MriOffice;

use Illuminate\Support\Facades\DB;

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
        $this->url = config('mri.base_url') . config('mri.endpoints.get_tenancies');
        return $this;
    }

    public function run()
    {
        dump($this->afterDate);
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
    
            $query = [
                'lastModifiedOnOrAfter' => $this->afterDate,
                'managementType' => self::MANAGEMENT_TYPE,
            ];
    
            $options = [
                'query' => $query
            ];
    
            $response = $client->request('GET', $this->url, $options);
    
            // TODO: handle request exceptions
            $data = json_decode($response->getBody()->getContents(), true);          
            
            $savedTenancyIds = $this->saveTenancies($office->id, $data);

            if (!empty($savedTenancyIds)){
                $message = sprintf('Updated %s mri applications', count($savedTenancyIds));
                dump($message);
                info($message, ['mri_application_ids' => $savedTenancyIds]);
                // TODO: send email?
            }
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
        $exceptionArray = [];
        $updatedTenancyIds = []; 

        foreach ($tenanciesData as $tenancy) {
            try {
                $mriApp = MriApplication::where('tenancy_id', $tenancy['id'])->first();

                if (!$mriApp) {
                    $mriApp = new MriApplication();
                }

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
                    if (count($tenancy['contacts']) == 1 || $contact['is_primary']){
                        $mriApp->title = $contact['title'];
                        $mriApp->first_name = $contact['first_name'];
                        $mriApp->last_name = $contact['last_name'];
                        $mriApp->email_address = $contact['email_address'];
                        $mriApp->mobile_phone_number = $contact['mobile_phone_number'];
                        $mriApp->home_number = $contact['phone_number'];
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
            } catch (\Exception $exception) {
                $exceptionArray[] = [
                    'officeId' => $officeId,
                    'exception' => $exception,
                    'data' => $tenancy
                ];
            }
        }

        if (!empty($exceptionArray)){
            foreach($exceptionArray as $e){
                dump($e['exception']->getMessage() . '|At line '. $e['exception']->getLine());
            }
        }

        return $updatedTenancyIds;
    }
}