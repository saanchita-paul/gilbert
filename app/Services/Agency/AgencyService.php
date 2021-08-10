<?php


namespace App\Services\Agency;


use App\Models\Agency;

class AgencyService
{
    public function createAgency(array $agency) {
        return Agency::query()->updateOrCreate($agency);
    }

    public function updateAgency() {

    }


}
