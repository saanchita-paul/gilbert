<?php

namespace PropertyMe\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use PropertyMe\PropertyMeLead;

class FetchContacts extends BasePropertyMeAPI
{

    /**
     * @var array $leads
     */
    private array $leads = [];

    private array $savedLead = [];

    /**
     * @throws Exception
     */
    public function fetchContacts(): static
    {
        $url = config('property_me.api_root_url') . config('property_me.get_contact_url');
        $query =  "?Timestamp=" . $this->getTimestamp();

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->getAccessToken(),
            ])->get($url . $query);

            $this->leads = $this->filterByAlreadySavedLead(json_decode($response->body(), true));
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }

        return $this;
    }

    /**
     *
     * @param array $leads
     * @return array
     */
    private function filterByAlreadySavedLead(array $leads): array
    {
        $leads = collect($leads);
        $ids = $leads->pluck('Id')->toArray();
        $alreadySavedIds = PropertyMeLead::query()->whereIn('lead_id', $ids)->pluck('lead_id')->toArray();
        return $leads->filter(function($value, $key) use ($alreadySavedIds) {
            return !in_array(data_get($value, 'Id'), $alreadySavedIds);
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
     * @throws Exception
     */
    private function getTimestamp(): string
    {
        $noOfDays = config('property_me.no_of_days');
        return now()->addDays($noOfDays)->format('U');
    }
}
