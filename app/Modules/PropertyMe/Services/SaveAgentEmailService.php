<?php

namespace PropertyMe\Services;

use App\Modules\PropertyMe\Services\FetchContactAPI;
use Exception;
use DB;
use App\Models\AgentProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class SaveAgentEmailService
{
    public function getLeadsWithoutEmail()
    {
        return DB::table('property_me_leads as pr')
            ->selectRaw("
                pr.id as `property_me_id`,
                pr.lead_id as `Property_me_lead_id`,
                pr.lot_id as `lot_id`,
                ca.id as `connection_application_id`,
                ofs.id as `office_id`,
                ofs.property_me_refresh_token as `refresh_token`
            ")
            ->leftJoin('connection_applications as ca', 'pr.connection_application_id', '=', 'ca.id')
            ->leftJoin('offices as ofs', 'ca.office_id', '=', 'ofs.id')
            ->whereNotNull('pr.lot_id')
            ->whereNotNull('pr.connection_application_id')
            ->whereNull('pr.agent_email')
            ->where('pr.created_at', '>=', '2022-04-01 00:00:00')
            ->whereNotNull('ca.office_id')
            ->whereNotNull('ofs.property_me_refresh_token')
            ->get()->toArray();
    }

    public function getEmailFromApi($lead)
    {
        $apiService = new FetchContactAPI((data_get($lead, 'refresh_token')));
        $lotMembers = $apiService->fetchTLotMembers(data_get($lead, 'lot_id'));
        return data_get($lotMembers, 'RegisteredEmail');
    }


    public function saveAgentEmailToPropertyMeTable($propId, $email)
    {
        DB::table('property_me_leads')
            ->where('id', $propId)
            ->update(['agent_email' => $email]);
    }

    public function getAgentID($email)
    {
        try {
            $agent = AgentProfile::whereHas(
                    'user',
                    fn (Builder $b) => $b->where('email', $email)
                )->firstOrFail();

            return $agent->id;

        } catch (\Exception $exception) {
            return null;
        }
    }

    public function saveAgentId($applicationID, $agentId)
    {
        DB::table('connection_applications')
            ->where('id', $applicationID)
            ->update(['created_by' => $agentId]);
    }
}
