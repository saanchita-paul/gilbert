<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\MriApplication;
use Illuminate\Support\Carbon;

class GetTaggedTenanciesService
{
    public const MANAGEMENT_TYPE = 'Residential';
    public const CONTACT_TYPE_TENANT = 'Tenant';
    public const PAGE_SIZE = 100;
    public const TAG_GROUP_NAME = 'Connect with HOOD';
    public const TAG_NAME = 'YES';

    /**
     * @var string|null
     */
    private ?string $accessToken;

    /**
     * @var string|null
     */
    private ?string $url;

    /**
     * @var ?int|null
     */
    private ?int $officeId;

    /**
     * @var ?int|null
     */
    private ?int $pageSize;

    /**
     * @var ?string|null
     */
    private ?string $tagGroupName;

    /**
     * @var ?string|null
     */
    private ?string $tagName;

    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    public function __construct()
    {
        $this->setURL();
        $this->exceptionHandler = new HandleExceptionService(self::class);
        $this->pageSize = !empty(config('mri.get_tenancies_page_size')) ? config('mri.get_tenancies_page_size') : self::PAGE_SIZE;
        $this->tagGroupName = !empty(config('mri.hood_tag_group_name')) ? config('mri.hood_tag_group_name') : self::TAG_GROUP_NAME;
        $this->tagName = !empty(config('mri.hood_tag_name')) ? config('mri.hood_tag_name') : self::TAG_NAME;
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
        $endpoint = empty(config('mri.endpoints.search_tenancies_by_tag')) ? '/residentialproperty/v1/tenancies/search' : config('mri.endpoints.search_tenancies_by_tag');
        $this->url = $url . $endpoint;
        return $this;
    }

    private function runAPI($token, $pageNo = 1)
    {
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
            'PageNo' => $pageNo,
            'PageSize' => $this->pageSize,
        ];

        $tag_group_name = $this->tagGroupName;
        $tag_name = $this->tagName;

        $body = [
            'groups' => [
                [
                    'name' => $tag_group_name,
                    'tags' => [$tag_name],
                ],
            ],
        ];

        $options = [
            'query' => $query,
            'json' => $body
        ];

        $response = $client->request('POST', $this->url, $options);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    private function getTaggedTenancies($token)
    {
        $tenancies = [];
        try {
            $pageNo = 1;
            $isLastPage = false;
            while (!$isLastPage) {
                $apiData = $this->runAPI($token, $pageNo);
                $tenancies = array_merge($apiData, $tenancies);
                $pageNo += 1;
                $isLastPage = count($apiData) !== self::PAGE_SIZE;
            }
        } catch (\Exception $e) {
            $this->exceptionHandler->addException($e);
        }
        return $tenancies;
    }

    public function run()
    {
        try {
            $mriOffices = (new GetOfficeService())->getMriOffices(true, $this->officeId ?? null);
            foreach ($mriOffices as $office) {
                $token = $office->key;
                $tenancies = $this->getTaggedTenancies($token);
                $this->saveMriApplications($tenancies, $office);
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

    /**
     * @param MriOffice mriOffice
     * @param array tenanciesData
     * @return array savedTenancyIds
     */
    public function saveMriApplications($tenanciesData, $mriOffice)
    {
        $savedTenancyIds = [];

        foreach ($tenanciesData as $tenancy) {
            try {
                $mriApp = MriApplication::firstOrNew(['tenancy_id' => $tenancy['id']]);

                $mriApp->mri_office_id = $mriOffice->id;
                $mriApp->tenancy_id = $tenancy['id'];
                $mriApp->name = $tenancy['name'];
                $mriApp->property = $tenancy['property'];
                $mriApp->rent_amount = $tenancy['rent']['amount'] ?? null;
                $mriApp->rent_period = $tenancy['rent']['period'] ?? null;
                $mriApp->prospect = $tenancy['prospect'];
                $mriApp->lease_detail_charge_tenants_water_usage = $tenancy['lease_detail']['charge_tenants_water_usage'] ?? null;

                if (!empty($tenancy['lease_start_date'])) {
                    $mriApp->lease_start_date = Carbon::parse($tenancy['lease_start_date']);
                }
                if (!empty($tenancy['lease_end_date'])) {
                    $mriApp->lease_end_date = Carbon::parse($tenancy['lease_end_date']);
                }
                if (!empty($tenancy['original_lease_start_date'])) {
                    $mriApp->original_lease_start_date = Carbon::parse($tenancy['original_lease_start_date']);
                }
                if (!empty($tenancy['vacate_date'])) {
                    $mriApp->vacate_date = Carbon::parse($tenancy['vacate_date']);
                }

                foreach ($tenancy['contacts'] as $contact) {
                    if (
                        !empty($mriApp->authorized_first_name) &&
                        !empty($mriApp->first_name) &&
                        $mriApp->authorized_first_name != $mriApp->first_name
                    ) {
                        break;
                    }

                    if (in_array(self::CONTACT_TYPE_TENANT, $contact['contact_types'])) {
                        if (empty($mriApp->first_name) || $contact['is_primary'] === true) {
                            $mriApp->title = $contact['title'];
                            $mriApp->first_name = $contact['first_name'];
                            $mriApp->last_name = $contact['last_name'];
                            $mriApp->email_address = $contact['email_address'];
                            $mriApp->mobile_phone_number = $contact['mobile_phone_number'];
                            $mriApp->home_number = $contact['phone_number'];
                            $mriApp->is_marketing = !$contact['no_marketing'];
                            $mriApp->preferred_phone_number = $contact['preferred_phone_number'];
                            if (
                                empty($contact['mobile_phone_number']) &&
                                empty($contact['phone_number']) &&
                                !empty($contact['preferred_phone_number'])
                            ) {
                                $isMobile = $this->checkIsMobile($contact['preferred_phone_number']);
                                if ($isMobile) {
                                    $mriApp->mobile_phone_number = $contact['preferred_phone_number'];
                                } else {
                                    $mriApp->home_number = $contact['preferred_phone_number'];
                                }
                            }
                        }
                        if (empty($mriApp->authorized_first_name) && $contact['is_primary'] === false) {
                            $mriApp->authorized_title = $contact['title'];
                            $mriApp->authorized_first_name = $contact['first_name'];
                            $mriApp->authorized_last_name = $contact['last_name'];
                            $mriApp->authorized_email_address = $contact['email_address'];
                            $mriApp->authorized_mobile_phone_number = $contact['mobile_phone_number'];
                            $mriApp->authorized_home_number = $contact['phone_number'];
                            $mriApp->authorized_preferred_phone_number = $contact['preferred_phone_number'];
                            if (
                                empty($contact['mobile_phone_number']) &&
                                empty($contact['phone_number']) &&
                                !empty($contact['preferred_phone_number'])
                            ) {
                                $isMobile = $this->checkIsMobile($contact['preferred_phone_number']);
                                if ($isMobile) {
                                    $mriApp->authorized_mobile_phone_number = $contact['preferred_phone_number'];
                                } else {
                                    $mriApp->authorized_home_number = $contact['preferred_phone_number'];
                                }
                            }
                        }
                    }
                }

                $mriApp->is_deleted = $tenancy['deleted'];
                $mriApp->is_archived = $tenancy['archived'];

                $mriApp->save();
                $savedTenancyIds[] = $mriApp->tenancy_id;
            } catch (\Exception $e) {
                $data = [
                    'officeId' => $mriOffice->id,
                    'tenancy' => $tenancy,
                ];
                $this->exceptionHandler->addException($e, $data);
            }
        }

        if (!empty($savedTenancyIds)) {
            $message = sprintf('Created %s mri applications', count($savedTenancyIds));
            info($message, ['mri_application_ids' => $savedTenancyIds]);
            $untagService = new UntagEntitiesService($mriOffice->key, $this->tagGroupName, $this->tagName, $savedTenancyIds);
            $untagService->run();
        }

        return $savedTenancyIds;
    }

    private function checkIsMobile($number)
    {
        $isMobile = false;
        $mobile_format = ['61', '+61', '04'];

        foreach ($mobile_format as $format) {
            if (str_starts_with($number, $format)) {
                $isMobile = true;
            }
        }

        return $isMobile;
    }
}
