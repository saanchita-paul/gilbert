<?php


namespace App\Services\Agency;


use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\OfficeCommission;

class UpdateOfficeService
{

    private int $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function updateOffice($data)
    {
        $office = Office::findOrFail($this->id);
        $office->update($data['office']);
        $office = $office->refresh()->toArray();
        $office['commissions'] = $this->updateCommissions($data['commissions'], $office);
        $office['agent'] = $this->updateAgent($data['agent']);
        return $office;
    }

    public function updateCommissions($commistions, $office)
    {
        foreach ($commistions as $commission) {
            if(isset($commission['id']))  {
                $officeCmtn = OfficeCommission::findOrFail($commission['id']);
                $officeCmtn->update($commission);
            } else {
                $commission['office_id'] = $this->id;
                $commission['agency_id'] = $office->agency_id;
                OfficeCommission::create($commission);
            }

        }
        return OfficeCommission::query()->where('office_id','=', $this->id)->get();
    }

    public function updateAgent($agentData)
    {
       $agent = AgentProfile::findOrFail($agentData['id']);
       $agent->update($agentData);
       return $agent->refresh();
    }
}
