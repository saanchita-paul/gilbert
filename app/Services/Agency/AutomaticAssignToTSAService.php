<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\HoodProfile;
use App\Models\User;
use App\Services\RolePermission;
use TSA\Services\TsaSendAppliationService;

class AutomaticAssignToTSAService
{
    public static function setAutomaticAssignToTSA(int $applicationId)
    {
        return (new static())->automaticAssignToTSA($applicationId);
    }

    public function automaticAssignToTSA(int $applicationId): void
    {
        // Get TSA ID
        $externalTSAId = User::query()
            ->where('email', env('EXTERNAL_TL_EMAIL'))
            ->first()->profile?->id;

        ConnectionApplication::query()
            ->where('id', $applicationId)
            ->update(['assigned_to' => $externalTSAId, 'status' => ConnectionApplication::STATUS_ASSIGNED]);

        if (in_array(HoodProfile::find($externalTSAId)->user->roles->first()?->name,
            [RolePermission::ROLE_EXTERNAL_HOOD_TEAM_LEAD])) {
            (new TsaSendAppliationService($applicationId))->sendApplication();
        }
    }

}
