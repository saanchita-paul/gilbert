<?php

namespace PropertyMe\services;

use Exception;
use Illuminate\Support\Facades\Http;
use PropertyMe\PropertyMeLead;

class FetchContacts extends BasePropertyMeAPI
{

    /**
     * @var array $leads
     */
    private array $leads = [];

    /**
     * @throws Exception
     */
    public function fetchContacts()
    {
        $url = config('property_me.api_root_url') . config('property_me.get_contact_url');
        $query =  "?Timestamp=" . $this->getTimestamp();

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->getAccessToken(),
            ])->get($url . $query);

            $this->leads = json_decode($response->body(), true);
        }
        catch (\Exception $exception)
        {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }


    private function createLead()
    {
        $data = [];
        foreach ($this->leads as $lead) {
            $lead = new PropertyMeLead();
            $lead->all_fields_dump = json_encode($lead);
            $lead->lead_id = data_get($lead, 'id');
            $lead->save();
        }
    }

    /**
     * @throws Exception
     */
    private function getTimestamp(): string
    {
        $noOfDays = config('property_me.no_of_days') ?? 1;
        $earlierDate = date('Y-m-d',strtotime("-$noOfDays days"));
        return (new \DateTime($earlierDate))->format('U');
    }
}
