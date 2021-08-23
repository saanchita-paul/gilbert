<?php


namespace App\Services\Agency;


use App\Models\Agency;

class AgencyService
{
    public function createAgency(array $agency) {
        return Agency::create($agency);
    }

    /**
     * Retrieve a single Agency instance by ID or fail.
     *
     * @param int $agencyId
     *
     * @return Agency
     */
    public function getAgency(int $agencyId): Agency
    {
        return Agency::findOrFail($agencyId);
    }

    public function updateAgency(array $updateAgency, int $agencyId)
    {
        $agency = Agency::findOrFail($agencyId);
        $agency->update($updateAgency);
        return $agency;
    }


}
