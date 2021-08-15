<?php


namespace App\Services\Agency;


use App\Models\Agency;
use App\Models\Office;
use App\Models\OfficeCommission;

class CreateOfficeAndAgency
{
    public function createOffice(array $office): Office
    {
        return Office::create($office);
    }

    public function createCommistions(array $officeCommissions, $officeId, $agencyId)
    {
        $commissions = [];
        foreach ($officeCommissions as $commission) {
            $commission['office_id'] = $officeId;
            $commissions[] = OfficeCommission::create($commission);
        }
        return $commissions;
    }

    public function createAgency(array $agency) {
        return Agency::create($agency);
    }


}
