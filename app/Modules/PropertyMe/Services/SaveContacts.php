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
    private array $tenancies;


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

    private FetchContactAPI $apiService;

    public function __construct(private string $refreshToken)
    {
        $this->apiService = new FetchContactAPI($this->refreshToken);
    }

    /**
     * @throws Exception
     */
    public function fetch(): static
    {

        $this->leads = $this->getNewHoodLeadOnly($this->apiService->fetchContacts()->getContacts());
        $this->tenancies = $this->apiService->fetchTenancies()->getTenancies();

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

            $leadId = data_get($leadData, 'Id');
            $lead = new PropertyMeLead();
            $lead->all_fields_dump = json_encode($leadData);
            $lead->lead_id = $leadId;

            if ($lotId = $this->getLotId($leadId) ) {
                $lotMembers = $this->apiService->fetchTLotMembers($lotId);
                $lead->lot_id = $lotId;
                $lead->agent_email = data_get($lotMembers, 'RegisteredEmail');
            }

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
    public function getTenancies(): array
    {
        return $this->tenancies;
    }


    /**
     * @param string $contactId
     * @return string|null
     */
    private function getLotId(string $contactId): ?string
    {
        return collect($this->tenancies)
            ->filter(fn($value) => data_get($value, 'ContactId') === $contactId)
            ->pluck('LotId')
            ->first();
    }

}
