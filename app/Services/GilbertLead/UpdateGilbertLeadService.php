<?php

namespace App\Services\GilbertLead;
use App\Models\ConnectionApplication;
use Illuminate\Http\Request;

class UpdateGilbertLeadService
{
    /*
     * update gilbert lead
     * */
    public function updateGilbert(Request $data, $id)
    {
        $value = [];
        $allowedProperty = ['title','first_name','middle_name', 'last_name', 'dob',
            'phone','email',
            'moving_date','address_unit','street_address','street_number','postcode','state','city','street_type','street_name_only','address_text',
            'billing_unit_number','billing_street_number','billing_street_name','billing_address_unit','billing_city','billing_state','billing_postcode','billing_street_type','billing_mannual_address','billing_state_short','billing_street_name_only','billing_address_text','billing_street_address',
            'property_type','tenancy_type','has_solar',
            'concession_card_type','concession_card_number','concession_start_date','concession_end_date'
        ];
            foreach ($allowedProperty as $field) {
                if (!empty(data_get($data, $field))) {
                    $value[$field] = data_get($data, $field);
                }
            }
        if(empty(data_get($value, 'middle_name'))) {
            $value['middle_name'] = '';
        }
        ConnectionApplication::query()
            ->where('id', $id)
            ->update($value);

    }
}
