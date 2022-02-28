<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\APILog;
use App\Models\Identification;
use function PHPSTORM_META\map;
use App\Models\ConnectionService;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ConnectionApplicationSecondaryACC;

class RolePermissionService
{
    CONST agency_agent = 'agency_agent';
    CONST agency_office_allocator = 'agency_office_allocator';
    CONST agency_office_admin = 'agency_office_admin';
    CONST agency_office_director = 'agency_office_director';

    CONST rolesAgency = [
        self::agency_agent,
        self::agency_office_allocator,
        self::agency_office_admin,
        self::agency_office_director
    ];

    const hood_admin = 'hood_admin';
    const hood_agent = 'hood_agent';
    CONST hood_team_lead = 'hood_team_lead';
    CONST hood_customer_rep = 'hood_customer_rep';

    const can_get_agency_details = 'can_get_agency_details';
    const can_get_application_metrics = 'can_get_application_metrics';
    const can_get_application_details = 'can_get_application_details';
    const can_submit_application = 'can_submit_application';
    const can_get_agent_list = 'can_get_agent_list';
    const can_update_agent_profile = 'can_update_agent_profile';

    const permissionAAgent = [
        self::can_get_agency_details,
        self::can_get_application_metrics ,
        self::can_get_application_details,
        self::can_submit_application ,
        self::can_get_agent_list ,
        self::can_update_agent_profile 
    ];



    
    const can_get_operation_report = 'can_get_operation_report';
    const can_get_export_report = 'can_get_export_report';
    const can_get_report_access_token = 'can_get_report_access_token';



    public function permissionAndRoleSetup(){

        if ($channelDoctor) {
            self::hood_admin->givePermissionTo([
                Permission::TREATMENT_PATIENT_AGREE,
                Permission::SEND_TREATMENT_SUMMARY_TO_PATIENT,
                Permission::CAN_SUBMIT_CASE,
                Permission::IS_DOCTOR,
                Permission::CHANGE_EXPRESS_UPDATE,
                Permission::CREATE_DEFAULT_INFORMATION_CURRENT_USER,
                Permission::FIND_PRESCRIPTION_INFORMATION_CURRENT_USER,
                Permission::SUBMIT_PHASE_REQUEST,
            ]);
        }

    }


    public function modifyRolesAndPermission(){

        $this->setRolesAndPermission(self::permissionAAgent, self::rolesAgency);

        return 'ok';
    }

    public function setRolesAndPermission($permissionAAgent, $rolesAgency){
        foreach ($permissionAAgent as $permissionName) {
            $permission = Permission::query()->firstOrNew([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
            $permission->save();
            $roles = Role::whereIn('name', $rolesAgency)->get();
            $permission->syncRoles($roles);
            // $model->AssignRole($agency_agent);
        }
    }

}
