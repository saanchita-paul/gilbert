<?php
namespace App\Services;

use App\Services\RolePermission;
class RolePermissionService
{
    public const CAN_GET_AGENCY_DETAILS = 'can_get_agency_details';
    public const CAN_GET_APPLICATION_METRICS = 'can_get_application_metrics';
    public const CAN_GET_APPLICATION_LIST = 'can_get_application_list';
    public const CAN_GET_APPLICATION_DETAILS = 'can_get_application_details';
    public const CAN_SUBMIT_APPLICATION = 'can_submit_application';
    public const CAN_GET_AGENT_LIST = 'can_get_agent_list';
    public const CAN_UPDATE_AGENT_PROFILE = 'can_update_agent_profile';
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
    public const CAN_GET_OPERATION_REPORT = 'can_get_operation_report';
    public const CAN_GET_EXPORT_REPORT = 'can_get_export_report';
    public const CAN_GET_REPORT_ACCESS_TOKEN = 'can_get_report_access_token';
    public const CAN_CREATE_NEW_APPLICATION = 'can_create_new_application';
    public const CAN_CHANGE_MANUAL_STATUS = 'can_change_manual_status';

    public static function allPermission()
    {
        return [
            //Agency Agent Permissions
            static::CAN_GET_AGENCY_DETAILS,
            static::CAN_GET_APPLICATION_METRICS,
            static::CAN_GET_APPLICATION_LIST,
            static::CAN_GET_APPLICATION_DETAILS,
            static::CAN_SUBMIT_APPLICATION,
            static::CAN_GET_AGENT_LIST,
            static::CAN_UPDATE_AGENT_PROFILE,

            //Hood Admin Permissions
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
            static::CAN_CHANGE_MANUAL_STATUS,

            //Hood Team lead Permissions
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

            //Hood Agent Permissions
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

            //Hood Customer Representative  Permissions
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
            static::CAN_CHANGE_MANUAL_STATUS,
        ];
    }

    public static function hoodAgentPermissions()
    {
        return [
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
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
        ];
    }

    public static function hoodCustomerRepExtraPermissions()
    {
        return [
            static::CAN_GET_OPERATION_REPORT,
            static::CAN_GET_EXPORT_REPORT,
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
            RolePermission::P_CAN_MANAGE_APPLICATION

        ];
    }

    public static function hoodExternalPermissions()
    {
        return [
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
}
