<?php


namespace App\Services;


use App\Models\ConnectionApplication;

class SeparateStreetNameService
{

    private $leads;

    private function getOldLeads()
    {
        $this->leads = ConnectionApplication::query()
            ->whereNull(['street_name_only', 'street_type'])
            ->get();
    }

    private function updateStreet()
    {
        foreach ($this->leads as $lead)
        {
            $fullStreetName = $this->separateStreetName($lead->street_name);
            $lead->street_name_only = $fullStreetName['street_name_only'];
            $lead->street_type = $fullStreetName['street_type'];
            $lead->save();

        }
    }

    private function separateStreetName(string $street_name): array
    {
        $street = [];
        $separateStreet = explode(' ', $street_name);
        $size = sizeof($separateStreet);
        if($size  === 1) {
            return ['street_name_only'=> $separateStreet[0], 'street_type'=> ''];
        }

        $street[1] = trim($separateStreet[$size - 1]);
        array_pop( $separateStreet);
        $street[0] = implode(' ', $separateStreet);
        return ['street_name_only'=> $street[0], 'street_type'=> $street[1]];
    }


    public function updateOldData()
    {
        $this->getOldLeads();
        $this->updateStreet();
    }


}
