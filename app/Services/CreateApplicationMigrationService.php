<?php
namespace App\Services;

use App\Services\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RolePermissionService;

class CreateApplicationMigrationService
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

        $submitApplicationPermission = Permission::whereName(RolePermissionService::CAN_SUBMIT_APPLICATION)->first();

        $createApplicationPermission = Permission::query()->firstOrNew([
            'name' => RolePermissionService::CAN_CREATE_NEW_APPLICATION,
            'guard_name' => 'web',
        ]);
        $createApplicationPermission->save();

        foreach ($agencyRoles as $role) {
            if ($role) {
                $role->revokePermissionTo($submitApplicationPermission);
                $role->givePermissionTo($createApplicationPermission);
            }
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

        $submitApplicationPermission = Permission::whereName(RolePermissionService::CAN_SUBMIT_APPLICATION)->first();

        $createApplicationPermission = Permission::whereName(RolePermissionService::CAN_CREATE_NEW_APPLICATION)->first();

        foreach ($agencyRoles as $role) {
            if ($role) {
                $role->givePermissionTo($submitApplicationPermission);
                $role->revokePermissionTo($createApplicationPermission);
            }
        }

        $createApplicationPermission = RolePermissionService::CAN_CREATE_NEW_APPLICATION;
        Permission::where('name', $createApplicationPermission)->delete();
    }
}
