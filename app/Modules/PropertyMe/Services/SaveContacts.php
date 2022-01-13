<?php

namespace PropertyMe\Services;

use App\Modules\PropertyMe\Services\FetchContactAPI;
use Exception;
use PropertyMe\PropertyMeLead;

class SaveContacts
{

    /**
     * @var array
     */
    private array $lots;

    /**
     * @param string $refreshToken
     */
    public function __construct(private string $refreshToken)
    {
    }

    /**
     * @var array $leads
     */
    private array $leads = [];

    /**
     * @var array
     */
    private array $savedLead = [];

    /**
     * @throws Exception
     */

    public function fetch(): static
    {
        $apiService = new FetchContactAPI($this->refreshToken);

        $this->leads = $this->getNewHoodLeadOnly($apiService->fetchContacts()->getContacts());
        $this->lots = $apiService->fetchLots()->getLots();

        info("raw contact", $apiService->getContacts());
        info("filtered contact", $this->leads);
        info("raw lots", $apiService->getContacts());

        return $this;
    }

    /**
     * taking new lead only new and which has labeled 'hood'
     *
     * @param array $leads
     * @return array
     */
    private function getNewHoodLeadOnly(array $leads): array
    {
        $leads = collect($leads);
        $ids = $leads->pluck('Id')->toArray();
        #todo:: need to find a better way to filter old leads
        $alreadySavedIds = PropertyMeLead::query()->whereIn('lead_id', $ids)->pluck('lead_id')->toArray();

        return $leads->filter(fn($value) => (
            strtolower(data_get($value, 'Labels')) == config('property_me.label')
            && !in_array(data_get($value, 'Id'), $alreadySavedIds)
        ))->toArray();
    }


    public function createLead(): static
    {
        foreach ($this->leads as $leadData) {
            $lead = new PropertyMeLead();
            $lead->all_fields_dump = json_encode($leadData);
            $lead->lead_id = data_get($leadData, 'Id');
            $lead->save();
            $this->savedLead[] = $lead;
        }

        return $this;
    }

    public function getSavedLeads(): array
    {
        return $this->savedLead;
    }

    /**
     * @return array
     */
    public function getLots(): array
    {
        return $this->lots;
    }

}
