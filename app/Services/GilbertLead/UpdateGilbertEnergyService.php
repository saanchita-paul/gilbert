<?php

namespace App\Services\GilbertLead;
use App\Models\ConnectionApplication;
use Illuminate\Http\Request;

class UpdateGilbertEnergyService
{

    /*
     * update energy
     * */
    public function updateEnergy(Request $data, $id)
    {
        $value = [];
        $allowedProperty = ['moving_date','has_electricity','inspection_time','is_power_life_support',
            'concession_card_type','concession_card_number','concession_start_date','concession_end_date'
        ];
        foreach ($allowedProperty as $field) {
            if (!empty(data_get($data, $field))) {
                $value[$field] = data_get($data, $field);
            }
        }
        ConnectionApplication::query()
            ->where('id', $id)
            ->update($value);
    }
}
