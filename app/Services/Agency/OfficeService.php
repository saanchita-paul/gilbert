<?php


namespace App\Services\Agency;


use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\HoodProfile;
use App\Models\Office;
use App\Models\OfficeAutoAssignTimeSlot;
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
        $office['hood_users'] = $this->getHoodUser();
        $office['time_slots'] = [$this->getOfficeTimeSlots()];
        return $office;
    }

    public function getOnlyOffice()
    {
        $office = Office::findOrFail($this->id)->toArray();
        $office['commissions'] = null;
        $office['agent'] = null;
        return $office;
    }

    public function getOfficeCommistion()
    {
        return OfficeCommission::query()->where('office_id', '=', $this->id)->get();
    }

    public function getAgent()
    {
        return AgentProfile::query()->with('user')->where('office_id', '=', $this->id)->first();
    }

    public function getHoodUser()
    {
        return HoodProfile::query()->get();
    }

    public function getOfficeTimeSlots()
    {
        $timeSlot = OfficeAutoAssignTimeSlot::query()->where('office_id', $this->id)->first();
        return [
            'id' => $timeSlot->id ?? null,
            'day' => $timeSlot->day ?? null,
            'start_time' => $timeSlot->start_time ?? null,
            'end_time' => $timeSlot->end_time ?? null,
        ];
    }
}
