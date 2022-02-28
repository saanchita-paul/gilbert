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
    
    //A Agent
    public const CAN_GET_AGENCY_DETAILS = 'can_get_agency_details';
    public const CAN_GET_APPLICATION_METRICS = 'can_get_application_metrics';
    public const CAN_GET_APPLICATION_LIST = 'can_get_application_list';
    public const CAN_GET_APPLICATION_DETAILS = 'can_get_application_details';
    public const CAN_SUBMIT_APPLICATION = 'can_submit_application';
    public const CAN_GET_AGENT_LIST = 'can_get_agent_list';
    public const CAN_UPDATE_AGENT_PROFILE = 'can_update_agent_profile';

    //H Admin
    public const CAN_GET_LEAD_METRICS = 'can_get_lead_metrics';
    public const CAN_GET_ASSIGNEE_LIST = 'can_get_assignee_list';
    public const CAN_ASSIGN_HOOD_USER = 'can_assign_hood_user';
    public const CAN_GET_APPLICATION_NOTES = 'can_get_application_notes';
    public const CAN_CREATE_NOTES = 'can_create_notes';
    public const CAN_GET_AUTHORIZED_PERSON = 'can_get_authorized_person';
    public const CAN_SAVE_AUTHORIZED_PERSON = 'can_save_authorized_person';
    public const CAN_GET_EA_PLANS = 'can_get_ea_plans';
    public const CAN_UPDATE_APPLICATION = 'can_update_application';
    public const CAN_ESCALATE_APPLICATION = 'can_escalate_application';
    public const CAN_CLOSE_APPLICATION = 'can_close_application';
    public const CAN_UPDATE_ADDRESS = 'can_update_address';
    public const CAN_UPDATE_SERVICE_PROVIDERS = 'can_update_service_providers';
    public const CAN_GET_ASSIGNED_USER = 'can_get_assigned_user';
    public const CAN_GET_NMI_MERN = 'can_get_nmi_mern';


    //H Team lead

    //H Agent
    public const CAN_GET_AGENCY_LIST = 'can_get_agency_list';
    public const CAN_CREATE_INDEPENDENT_AGENCY = 'can_create_independent_agency';
    public const CAN_CREATE_FRANCHISED_AGENCY = 'can_create_franchised_agency';
    public const CAN_GET_OFFICES = 'can_get_offices';
    public const CAN_UPDATE_AGENCY = 'can_update_agency';
    public const CAN_CREATE_NEW_OFFICE = 'can_create_new_office';
    public const CAN_GET_OFFICE_METRICS = 'can_get_office_metrics';
    public const CAN_GET_OFFICE_DETAILS = 'can_get_office_details';
    public const CAN_GET_OFFICE_USER_LIST = 'can_get_office_user_list';
    public const CAN_CREATE_OFFICE_USER = 'can_create_office_user';
    public const CAN_UPDATE_OFFICE = 'can_update_office';
    public const CAN_UPDATE_OFFICE_AGENT = 'can_update_office_agent';
    public const CAN_SEND_CONFIRRMATION_MAIL = 'can_send_confirrmation_mail';

    //H Customer Representative
    public const CAN_GET_OPERATION_REPORT = 'can_get_operation_report';
    public const CAN_GET_EXPORT_REPORT = 'can_get_export_report';
    public const CAN_GET_REPORT_ACCESS_TOKEN = 'can_get_report_access_token';

    public static function allPermission()
    {
        return [
            //A Agent
            static::CAN_GET_AGENCY_DETAILS,
            static::CAN_GET_APPLICATION_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_AGENT_LIST,
            static::CAN_UPDATE_AGENT_PROFILE,
        
            //H Admin
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
            static::CAN_GET_LEAD_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_GET_ASSIGNEE_LIST,
            static::CAN_ASSIGN_HOOD_USER,
            static::CAN_GET_APPLICATION_NOTES,
            static::CAN_CREATE_NOTES,
            static::CAN_GET_AUTHORIZED_PERSON,
            static::CAN_SAVE_AUTHORIZED_PERSON,
            static::CAN_GET_EA_PLANS,
            static::CAN_UPDATE_APPLICATION,
            static::CAN_ESCALATE_APPLICATION,
            static::CAN_CLOSE_APPLICATION,
            static::CAN_UPDATE_ADDRESS,
            static::CAN_UPDATE_SERVICE_PROVIDERS,
            static::CAN_GET_ASSIGNED_USER,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_NMI_MERN,
        
        
            //H Team lead
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
            static::CAN_GET_LEAD_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_GET_ASSIGNEE_LIST,
            static::CAN_ASSIGN_HOOD_USER,
            static::CAN_GET_APPLICATION_NOTES,
            static::CAN_CREATE_NOTES,
            static::CAN_GET_AUTHORIZED_PERSON,
            static::CAN_SAVE_AUTHORIZED_PERSON,
            static::CAN_GET_EA_PLANS,
            static::CAN_UPDATE_APPLICATION,
            static::CAN_ESCALATE_APPLICATION,
            static::CAN_CLOSE_APPLICATION,
            static::CAN_UPDATE_ADDRESS,
            static::CAN_UPDATE_SERVICE_PROVIDERS,
            static::CAN_GET_ASSIGNED_USER,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_NMI_MERN,
        
            //H Agent
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
            static::CAN_GET_APPLICATION_METRICS,
            static::CAN_GET_AGENCY_LIST,
            static::CAN_CREATE_INDEPENDENT_AGENCY,
            static::CAN_CREATE_FRANCHISED_AGENCY,
            static::CAN_GET_AGENCY_DETAILS,
            static::CAN_GET_OFFICES,
            static::CAN_UPDATE_AGENCY,
            static::CAN_CREATE_NEW_OFFICE,
            static::CAN_GET_OFFICE_METRICS,
            static::CAN_GET_OFFICE_DETAILS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_OFFICE_USER_LIST,
            static::CAN_CREATE_OFFICE_USER,
            static::CAN_UPDATE_OFFICE,
            static::CAN_UPDATE_OFFICE_AGENT,
            static::CAN_SEND_CONFIRRMATION_MAIL,
        
            //H Customer Representative
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
        ];
    }

    public static function allAgencyPermission()
    {
        return [
            static::CAN_GET_AGENCY_DETAILS,
            static::CAN_GET_APPLICATION_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_AGENT_LIST,
            static::CAN_UPDATE_AGENT_PROFILE,
        ];
    }

    public static function hoodAdminPermissions()
    {
        return [
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
            static::CAN_GET_LEAD_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_GET_ASSIGNEE_LIST,
            static::CAN_ASSIGN_HOOD_USER,
            static::CAN_GET_APPLICATION_NOTES,
            static::CAN_CREATE_NOTES,
            static::CAN_GET_AUTHORIZED_PERSON,
            static::CAN_SAVE_AUTHORIZED_PERSON,
            static::CAN_GET_EA_PLANS,
            static::CAN_UPDATE_APPLICATION,
            static::CAN_ESCALATE_APPLICATION,
            static::CAN_CLOSE_APPLICATION,
            static::CAN_UPDATE_ADDRESS,
            static::CAN_UPDATE_SERVICE_PROVIDERS,
            static::CAN_GET_ASSIGNED_USER,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_NMI_MERN,
        ];
    }

    public static function hoodAgentPermissions()
    {
        return [
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
            static::CAN_GET_APPLICATION_METRICS,
            static::CAN_GET_AGENCY_LIST,
            static::CAN_CREATE_INDEPENDENT_AGENCY,
            static::CAN_CREATE_FRANCHISED_AGENCY,
            static::CAN_GET_AGENCY_DETAILS,
            static::CAN_GET_OFFICES,
            static::CAN_UPDATE_AGENCY,
            static::CAN_CREATE_NEW_OFFICE,
            static::CAN_GET_OFFICE_METRICS,
            static::CAN_GET_OFFICE_DETAILS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_OFFICE_USER_LIST,
            static::CAN_CREATE_OFFICE_USER,
            static::CAN_UPDATE_OFFICE,
            static::CAN_UPDATE_OFFICE_AGENT,
            static::CAN_SEND_CONFIRRMATION_MAIL,
        ];
    }

    public static function hoodTeamLeadPermissions()
    {
        return [
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
            static::CAN_GET_LEAD_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_GET_ASSIGNEE_LIST,
            static::CAN_ASSIGN_HOOD_USER,
            static::CAN_GET_APPLICATION_NOTES,
            static::CAN_CREATE_NOTES,
            static::CAN_GET_AUTHORIZED_PERSON,
            static::CAN_SAVE_AUTHORIZED_PERSON,
            static::CAN_GET_EA_PLANS,
            static::CAN_UPDATE_APPLICATION,
            static::CAN_ESCALATE_APPLICATION,
            static::CAN_CLOSE_APPLICATION,
            static::CAN_UPDATE_ADDRESS,
            static::CAN_UPDATE_SERVICE_PROVIDERS,
            static::CAN_GET_ASSIGNED_USER,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_NMI_MERN,
        
        ];
    }

    public static function hoodCustomerRepPermissions()
    {
        return [
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
            static::CAN_GET_REPORT_ACCESS_TOKEN,
        ];
    }


    
    
    // CONST agency_agent = 'agency_agent';
    // CONST agency_office_allocator = 'agency_office_allocator';
    // CONST agency_office_admin = 'agency_office_admin';
    // CONST agency_office_director = 'agency_office_director';

    // CONST rolesAgency = [
    //     self::agency_agent,
    //     self::agency_office_allocator,
    //     self::agency_office_admin,
    //     self::agency_office_director
    // ];

    // const hood_admin = 'hood_admin';
    // const hood_agent = 'hood_agent';
    // CONST hood_team_lead = 'hood_team_lead';
    // CONST hood_customer_rep = 'hood_customer_rep';

    // const can_get_agency_details = 'can_get_agency_details';
    // const can_get_application_metrics = 'can_get_application_metrics';
    // const can_get_application_details = 'can_get_application_details';
    // const can_submit_application = 'can_submit_application';
    // const can_get_agent_list = 'can_get_agent_list';
    // const can_update_agent_profile = 'can_update_agent_profile';

    // const permissionAAgent = [
    //     self::can_get_agency_details,
    //     self::can_get_application_metrics ,
    //     self::can_get_application_details,
    //     self::can_submit_application ,
    //     self::can_get_agent_list ,
    //     self::can_update_agent_profile 
    // ];



    
    // const can_get_operation_report = 'can_get_operation_report';
    // const can_get_export_report = 'can_get_export_report';
    // const can_get_report_access_token = 'can_get_report_access_token';



    // public function permissionAndRoleSetup(){

    //     if ($channelDoctor) {
    //         self::hood_admin->givePermissionTo([
    //             Permission::TREATMENT_PATIENT_AGREE,
    //             Permission::SEND_TREATMENT_SUMMARY_TO_PATIENT,
    //             Permission::CAN_SUBMIT_CASE,
    //             Permission::IS_DOCTOR,
    //             Permission::CHANGE_EXPRESS_UPDATE,
    //             Permission::CREATE_DEFAULT_INFORMATION_CURRENT_USER,
    //             Permission::FIND_PRESCRIPTION_INFORMATION_CURRENT_USER,
    //             Permission::SUBMIT_PHASE_REQUEST,
    //         ]);
    //     }

    // }


    // public function modifyRolesAndPermission(){

    //     $this->setRolesAndPermission(self::permissionAAgent, self::rolesAgency);

    //     return 'ok';
    // }

    // public function setRolesAndPermission($permissionAAgent, $rolesAgency){
    //     foreach ($permissionAAgent as $permissionName) {
    //         $permission = Permission::query()->firstOrNew([
    //             'name' => $permissionName,
    //             'guard_name' => 'web',
    //         ]);
    //         $permission->save();
    //         $roles = Role::whereIn('name', $rolesAgency)->get();
    //         $permission->syncRoles($roles);
    //         // $model->AssignRole($agency_agent);
    //     }
    // }

}
