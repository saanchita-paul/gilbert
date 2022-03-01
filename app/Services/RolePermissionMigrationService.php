<?php
namespace App\Services;

use App\Services\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RolePermissionService;

class RolePermissionMigrationService
{
    public static function setPermissions()
    {
        $agency_agent = Role::whereName(RolePermission::ROLE_AGENCY_AGENT)->first();
        $agency_office_allocator = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_ALLOCATOR)->first();
        $agency_office_admin = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_ADMIN)->first();
        $agency_office_director = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_DIRECTOR)->first();
        $agency_office_property_manager = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_PROPERTY_MANAGER)->first();
        $agency_office_senior_property_manager = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER)->first();
        $agency_assistant_property_manager = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_ASSISTANT_PROPERTY_MANAGER)->first();
        $agency_office_real_estate_agent = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT)->first();
        $agency_office_business_development_manager = Role::whereName(RolePermission::ROLE_AGENCY_BUSINESS_DEVELOPMENT_MANAGER)->first();
        $agency_sales_pa = Role::whereName(RolePermission::ROLE_AGENCY_SALES_PA)->first();
        $agency_receptionist = Role::whereName(RolePermission::ROLE_AGENCY_RECEPTIONIST)->first();

        $hood_admin = Role::whereName(RolePermission::ROLE_HOOD_ADMIN)->first();
        $hood_agent = Role::whereName(RolePermission::ROLE_HOOD_AGENT)->first();
        $hood_team_lead = Role::whereName(RolePermission::ROLE_HOOD_TEAM_LEAD)->first();
        $hood_customer_rep = Role::whereName(RolePermission::ROLE_HOOD_CUSTOMER_REP)->first();

        $hood_external_team_lead = Role::whereName(RolePermission::ROLE_EXTERNAL_HOOD_TEAM_LEAD)->first();
        $hood_external_customer_rep = Role::whereName(RolePermission::ROLE_EXTERNAL_HOOD_CUSTOMER_REP)->first();

        $agencyRoles = [
            $agency_agent ? $agency_agent : null,
            $agency_office_allocator ? $agency_office_allocator : null,
            $agency_office_admin ? $agency_office_admin : null,
            $agency_office_director ? $agency_office_director : null,
            $agency_office_property_manager ? $agency_office_property_manager : null,
            $agency_office_senior_property_manager ? $agency_office_senior_property_manager : null,
            $agency_assistant_property_manager ? $agency_assistant_property_manager : null,
            $agency_office_real_estate_agent ? $agency_office_real_estate_agent : null,
            $agency_office_business_development_manager ? $agency_office_business_development_manager : null,
            $agency_sales_pa ? $agency_sales_pa : null,
            $agency_receptionist ? $agency_receptionist : null
        ];


        $permissions = RolePermissionService::allPermission();
        foreach ($permissions as $permission) {
            $model = Permission::query()->firstOrNew([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
            $model->save();
        }

        foreach ($agencyRoles as $role) {
            if ($role) {
                $role->givePermissionTo(RolePermissionService::allAgencyPermission());
            }
        }

        if ($hood_admin) {
            $hood_admin->givePermissionTo(RolePermissionService::hoodAdminPermissions());
        }

        if ($hood_agent) {
            $hood_agent->givePermissionTo(RolePermissionService::hoodAgentPermissions());
        }

        if ($hood_team_lead) {
            $hood_team_lead->givePermissionTo(RolePermissionService::hoodTeamLeadPermissions());
        }

        if ($hood_customer_rep) {
            $hood_customer_rep->givePermissionTo(RolePermissionService::hoodCustomerRepPermissions());
        }

        if ($hood_external_team_lead) {
            $hood_external_team_lead->givePermissionTo(RolePermissionService::hoodExternalPermissions());
        }

        if ($hood_external_customer_rep) {
            $hood_external_customer_rep->givePermissionTo(RolePermissionService::hoodExternalPermissions());
        }
    }

    public static function revokePermissions()
    {
        $agency_agent = Role::whereName(RolePermission::ROLE_AGENCY_AGENT)->first();
        $agency_office_allocator = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_ALLOCATOR)->first();
        $agency_office_admin = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_ADMIN)->first();
        $agency_office_director = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_DIRECTOR)->first();
        $agency_office_property_manager = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_PROPERTY_MANAGER)->first();
        $agency_office_senior_property_manager = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER)->first();
        $agency_assistant_property_manager = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_ASSISTANT_PROPERTY_MANAGER)->first();
        $agency_office_real_estate_agent = Role::whereName(RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT)->first();
        $agency_office_business_development_manager = Role::whereName(RolePermission::ROLE_AGENCY_BUSINESS_DEVELOPMENT_MANAGER)->first();
        $agency_sales_pa = Role::whereName(RolePermission::ROLE_AGENCY_SALES_PA)->first();
        $agency_receptionist = Role::whereName(RolePermission::ROLE_AGENCY_RECEPTIONIST)->first();

        $hood_admin = Role::whereName(RolePermission::ROLE_HOOD_ADMIN)->first();
        $hood_agent = Role::whereName(RolePermission::ROLE_HOOD_AGENT)->first();
        $hood_team_lead = Role::whereName(RolePermission::ROLE_HOOD_TEAM_LEAD)->first();
        $hood_customer_rep = Role::whereName(RolePermission::ROLE_HOOD_CUSTOMER_REP)->first();

        $hood_external_team_lead = Role::whereName(RolePermission::ROLE_EXTERNAL_HOOD_TEAM_LEAD)->first();
        $hood_external_customer_rep = Role::whereName(RolePermission::ROLE_EXTERNAL_HOOD_CUSTOMER_REP)->first();

        $agencyRoles = [
            $agency_agent ? $agency_agent : null,
            $agency_office_allocator ? $agency_office_allocator : null,
            $agency_office_admin ? $agency_office_admin : null,
            $agency_office_director ? $agency_office_director : null,
            $agency_office_property_manager ? $agency_office_property_manager : null,
            $agency_office_senior_property_manager ? $agency_office_senior_property_manager : null,
            $agency_assistant_property_manager ? $agency_assistant_property_manager : null,
            $agency_office_real_estate_agent ? $agency_office_real_estate_agent : null,
            $agency_office_business_development_manager ? $agency_office_business_development_manager : null,
            $agency_sales_pa ? $agency_sales_pa : null,
            $agency_receptionist ? $agency_receptionist : null
        ];

        foreach ($agencyRoles as $role) {
            if ($role) {
                $role->revokePermissionTo(RolePermissionService::allAgencyPermission());
            }
        }

        if ($hood_admin) {
            $hood_admin->revokePermissionTo(RolePermissionService::hoodAdminPermissions());
        }

        if ($hood_agent) {
            $hood_agent->revokePermissionTo(RolePermissionService::hoodAgentPermissions());
        }

        if ($hood_team_lead) {
            $hood_team_lead->revokePermissionTo(RolePermissionService::hoodTeamLeadPermissions());
        }

        if ($hood_customer_rep) {
            $hood_customer_rep->revokePermissionTo(RolePermissionService::hoodCustomerRepPermissions());
        }

        if ($hood_external_team_lead) {
            $hood_external_team_lead->revokePermissionTo(RolePermissionService::hoodExternalPermissions());
        }

        if ($hood_external_customer_rep) {
            $hood_external_customer_rep->revokePermissionTo(RolePermissionService::hoodExternalPermissions());
        }

        $permissions = RolePermissionService::allPermission();
        $model = Permission::whereIn('name', $permissions)->delete();
    }
}
