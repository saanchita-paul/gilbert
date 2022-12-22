<?php

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\Office;
use App\Models\OfficeAutoAssignTimeSlot;
use App\Models\OfficeCommission;
use Carbon\Carbon;
use App\Services\MRI\HandleMRIOfficeService;

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
        $mriOffice = $data['mri_office'];
        $service = new HandleMRIOfficeService($this->id);
        // Update MRI office
        if (
            $mriOffice && !empty($mriOffice['key'])
            && !empty($mriOffice['company_name'])
            && !empty($mriOffice['activation_date'])
        ) {
            $service->saveMRIOffice($mriOffice);
        } else {
            $service->resetMRIOffice();
        }

        if ($data['office']['agency_type'] == 0) {
            $agency = Agency::findOrFail($data['office']['agency_id']);
            $agency->update(['name' => $data['office']['agency_name']]);
        }

        $office = $office->refresh()->toArray();
        $office['commissions'] = $this->updateCommissions($data['commissions'], $office);
        $office['agent'] = $this->updateAgent($data['agent']);
        $office['time_slots'] = $this->updateTimeSlots($data['time_slots'], $data['office']['is_chatbot_office']);
        return $office;
    }

    public function updateTimeSlots($timeSlots, $isChatbotOffice)
    {
        $weekDays = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7,
        ];

        if ($isChatbotOffice && count($timeSlots) > 0) {
            foreach ($weekDays as $weekDayName => $weekDay) {
                OfficeAutoAssignTimeSlot::updateOrCreate([
                    'office_id' => $this->id,
                    'day' => $weekDayName,
                ], [
                    'start_time' => Carbon::parse($timeSlots[0]['start_time'])->format('H:i:s'),
                    'end_time' => Carbon::parse($timeSlots[0]['end_time'])->format('H:i:s'),
                ]);
            }
        } else {
            OfficeAutoAssignTimeSlot::query()->where('office_id', $this->id)->delete();
        }

        return OfficeAutoAssignTimeSlot::query()->where('office_id', $this->id)->get();
    }

    public function updateCommissions($commistions, $office)
    {
        foreach ($commistions as $commission) {
            if (isset($commission['id'])) {
                $officeCmtn = OfficeCommission::findOrFail($commission['id']);
                $officeCmtn->update($commission);
            } else {
                $commission['office_id'] = $this->id;
                $commission['agency_id'] = $office['agency_id'];
                $commission['type'] = OfficeCommission::Type[$commission['text']];
                OfficeCommission::create($commission);
            }

        }
        return OfficeCommission::query()->where('office_id', '=', $this->id)->get();
    }

    public function updateAgent($agentData)
    {
        $agent = AgentProfile::findOrFail($agentData['id']);
        $agent->update($agentData);
        $agent->user->update(["email" => $agentData['email']]);
        return $agent->refresh();
    }
}
