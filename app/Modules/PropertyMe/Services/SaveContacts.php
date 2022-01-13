<?php

namespace PropertyMe\Services;

use App\Modules\PropertyMe\Services\FetchContactAPI;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PropertyMe\PropertyMeLead;

class SaveContacts
{

    private array $lots;

    public function __construct(private string $refreshToken)
    {
    }

    /**
     * @var array $leads
     */
    private array $leads = [];

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

    /** filterByAlreadySavedLead and also filter hood leads only
     *
     * @param array $leads
     * @return array
     */
    private function getNewHoodLeadOnly(array $leads): array
    {
        $leads = collect($leads);
        $ids = $leads->pluck('Id')->toArray();
        $alreadySavedIds = PropertyMeLead::query()->whereIn('lead_id', $ids)->pluck('lead_id')->toArray();
        return $leads->filter(function($value, $key) use ($alreadySavedIds) {
            return strtolower(data_get($value, 'Labels')) == '|hood|' && !in_array(data_get($value, 'Id'), $alreadySavedIds);
//            return true;
        })->toArray();
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
