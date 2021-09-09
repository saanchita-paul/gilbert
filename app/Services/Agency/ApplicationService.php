<?php
namespace App\Services\Agency;

use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\User;
use App\Services\Agency\CreateOfficeAndAgency;
use App\Services\Sales\PostSalesService;
use Illuminate\Console\Application;
use function PHPUnit\Framework\isNull;

class ApplicationService
{
    public function createApplication(array $application, User $user){

        $agentProfile = $user->profile;
        $application['office_id'] = $agentProfile->office_id;
        $application['agency_id'] = $agentProfile->agency_id;
        $application['created_by'] = $agentProfile->id;
        $application['status'] = ConnectionApplication::STATUS_UNASSIGNED;

        /** @var $newApplication ConnectionApplication */
        $newApplication = ConnectionApplication::create($application);
        $connectionService = [];

        foreach ($application['service_interests'] as $service) {
            $connectionService[] = ConnectionService::create(
                ['service_type'=>$service['service_type'],
                'connection_application_id'=>$newApplication->id]
            );
        }

        return $newApplication;

    }


    public function getNotes($application_id)
    {
        return ApplicationNote::query()->where('connection_application_id','=', $application_id)->get();
    }

    public function createNotes(array $note, User $user, $applicationId)
    {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $user->id;
        if($note['title'] == null) {
            $note['title'] = 'Note by '.$user->profile->first_name;
        }
        return ApplicationNote::create($note);
    }

    public function updateApplication(array $application, int $applicationId) {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->assigned_to = $application['agent_profile_id'];
        $existingApplication->status = ConnectionApplication::STATUS_SUBMITTED;
        $existingApplication->save();
    }

    public function updateAddress(array $address, int $applicationId) {
        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->address_text = $address['address_text'];
        $existingApplication->street_address = $address['street_address'];
        $existingApplication->street_name = $address['street_name'];
        $existingApplication->street_number = empty($address['street_address']) ? null : $address['street_number'] ;
        $existingApplication->unit_number = empty($address['street_address']) ? null : $address['unit_number'];
        $existingApplication->city = $address['city'];
        $existingApplication->postcode = $address['postcode'];
        $existingApplication->state = $address['state'];
        $existingApplication->country = $address['country'];
        $existingApplication->mirn = $address['mirn'] ?? $existingApplication->mirn;
        $existingApplication->nmi = $address['nmi'] ?? $existingApplication->nmi;
        $existingApplication->save();

        return $existingApplication;
    }

    public function assignUser(string $agentId, int $applicationId)
    {
        ConnectionApplication::query()
            ->where('id', $applicationId)
            ->update(['assigned_to' => $agentId, 'status' => ConnectionApplication::STATUS_ASSIGNED]);

        return $this->findApplications($applicationId);
    }


    public function findApplications(int $id)
    {
        return ConnectionApplication::query()
            ->where('id', $id)
            ->with('connectionServices', 'identification')
            ->first();
    }

    public function updateConnectionService(array $serviceList, $id)
    {
        ConnectionService::query()->where('connection_application_id', '=', $id)->delete();
        foreach ($serviceList as $service) {
            ConnectionService::create(['service_type'=>$service, 'connection_application_id'=>$id]);
        }
    }

    public function createIdentification($identificationData, $id)
    {
        $identification = Identification::where('connection_application_id' , $id);

        if($identification->first())
        {
           return $identification->update($identificationData);
        }
        $identificationData['connection_application_id'] = $id;
        return Identification::create($identificationData);

    }


    public function submit(array $applications, $id)
    {
        $lead = $applications['lead'];
        $lead['status'] = ConnectionApplication::STATUS_SUBMITTED;
        $lead['moving_date'] = "2021-09-08";
        $lead = array_merge($lead, ['plan_type' => ConnectionApplication::PLAN_TYPE_MAPPER[$lead['plan_type']]]);
        $existLead = ConnectionApplication::findOrFail($id);
        $existLead->update($lead);
        $this->createIdentification($lead['identification'], $id);
        $this->updateConnectionService($lead['service_interests'], $id);;
        return $existLead;
    }


    public function updateEscalateApplication(array $application, int $applicationId, User $user) {

        $existingApplication = ConnectionApplication::find($applicationId);
        $existingApplication->reason = $application['reason'];
//        $existingApplication->status = ConnectionApplication::STATUS_MAPPING[$application['status']];
        $existingApplication->status = ConnectionApplication::STATUS_ESCALATED;
        $existingApplication->save();

        $allicationNoteService = new ApplicationNoteService($user);
        $eacalateNote = [];
        $eacalateNote['text'] = $application['reason'];
        $eacalateNote['type'] = 'Escalated';

        $allicationNoteService->createNotes($eacalateNote, $applicationId);

        return $existingApplication;
    }

    public function updateSoleField(array $application, $id)
    {
        $existLead = ConnectionApplication::findOrFail($id);
        $isIdentification = $application['identification'];
        $isService = $application['isService'];

        unset($application['identification']);
        unset($application['isService']);

        if($isIdentification)
        {
            $this->createIdentification($application, $id);
        }
        else if($isService)
        {
            $this->updateConnectionService($application['service_types'], $id);
        }
        else
        {
            $existLead->update($application);
        }

        return $existLead->refresh();
    }
}
