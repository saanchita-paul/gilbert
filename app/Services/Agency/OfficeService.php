<?php


namespace App\Services\Agency;


use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\OfficeCommission;

class OfficeService
{

    private int $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getOffice()
    {
        $office = Office::findOrFail($this->id)->toArray();
        $office['agency'] = Agency::find($office['agency_id']);
        $office['commissions'] = $this->getOfficeCommistion();
        $office['agent'] = $this->getAgent();
        return $office;
    }

    public function getOfficeCommistion()
    {
        return OfficeCommission::query()->where('office_id','=', $this->id)->get();
    }

    public function getAgent()
    {
        return AgentProfile::query()->where('office_id','=', $this->id)->first()->load('user');
    }
}
