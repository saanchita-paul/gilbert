<?php


namespace App\Services\Agency;



use App\Services\MRI\HandleMRIOfficeService;

class IndepentAgencyService
{

    public function create($inputData)
    {
        $agencySvc = new AgencyService();
        $ofcAndAgencySvc = new CreateOfficeAndAgency();
        $agentAndUserSvc = new CreateAgentAndUser();

        $officeData = $inputData['office'];
        $agencyData = $inputData['agency'];
        $officeCommissionsData = $inputData['office_commissions'];
        $mriOffice = $inputData['mri_office'];
        $agency = $agencySvc->createAgency($agencyData);

        $officeData['agency_id'] = $agency->id;
        $office = $ofcAndAgencySvc->createOffice($officeData);
        // Save MRI office
        if ($mriOffice) {
            $service = new HandleMRIOfficeService($office->id);
            $service->saveMRIOffice($mriOffice);
        }

        $officeAllocatorData = $inputData['agent'];
        $officeAllocatorData['agency_id'] = $agency->id;
        $officeAllocatorData['office_id'] = $office->id;
        $agent = $agentAndUserSvc->createAgent($officeAllocatorData);
        $officeAllocatorData['profile_id'] = $agent->id;
        $agent = $agentAndUserSvc->createUser($officeAllocatorData);
        $commissions = $ofcAndAgencySvc->createCommistions($officeCommissionsData, $office->id, $officeData['agency_id']);
        return $agency;

    }
}
