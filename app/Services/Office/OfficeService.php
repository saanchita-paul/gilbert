<?php


namespace App\Services\Office;

use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\OfficeCommission;

class OfficeService
{
    public function createOffice(array $office) {
        return Office::create($office);
    }

    public function createAgent( array $agenct) {
        return AgentProfile::create($agenct);
    }

    public function createCommistions(array $officeCommissions, $officeId, $agencyId) {
        $commissions = [];
        foreach ($officeCommissions as $commission) {
                $commission['office_id'] = $officeId;
                $commissions[] = OfficeCommission::create($commission);
              }
        return $commissions;
    }


}
