<?php

namespace Database\Seeders;

use App\Services\RolePermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public const ROLES = [
        #hood
        RolePermission::ROLE_HOOD_ADMIN,
        RolePermission::ROLE_HOOD_AGENT,
        RolePermission::ROLE_AGENCY_AGENT,
        RolePermission::ROLE_HOOD_TEAM_LEAD,
        RolePermission::ROLE_HOOD_CUSTOMER_REP,

        #agency
        RolePermission::ROLE_AGENCY_OFFICE_ALLOCATOR,
        RolePermission::ROLE_AGENCY_OFFICE_ADMIN,
        RolePermission::ROLE_AGENCY_OFFICE_DIRECTOR,
        RolePermission::ROLE_AGENCY_OFFICE_PROPERTY_MANAGER,
        RolePermission::ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER,
        RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT,
        RolePermission::ROLE_AGENCY_AGENT,
        RolePermission::ROLE_AGENCY_OFFICE_ASSISTANT_PROPERTY_MANAGER,
        RolePermission::ROLE_AGENCY_BUSINESS_DEVELOPMENT_MANAGER,
        RolePermission::ROLE_AGENCY_SALES_PA,
        RolePermission::ROLE_AGENCY_RECEPTIONIST,
        RolePermission::ROLE_HOOD_CHATBOT_USER,

    ];

    public const PERMISSIONS = [
        RolePermission::ROLE_HOOD_ADMIN => [
            RolePermission::P_HOOD_ADMIN_CORE,
            RolePermission::P_CAN_MANAGE_APPLICATION,
            RolePermission::P_CAN_MANAGE_AGENCY,
            RolePermission::P_CAN_SWITCH_AUTO_CHATBOT_ASSIGN,
        ],
        RolePermission::ROLE_HOOD_AGENT => [
            RolePermission::P_HOOD_AGENT_CORE,
            RolePermission::P_CAN_MANAGE_AGENCY,
        ],
        RolePermission::ROLE_HOOD_TEAM_LEAD => [
            RolePermission::P_HOOD_TEAM_LEAD_CORE,
            RolePermission::P_CAN_MANAGE_APPLICATION

        ],

        RolePermission::ROLE_HOOD_CUSTOMER_REP => [
            RolePermission::P_HOOD_CUSTOMER_REP_CORE,
            RolePermission::P_CAN_MANAGE_APPLICATION
        ],

        RolePermission::ROLE_AGENCY_AGENT => [
            RolePermission::P_AGENCY_AGENT_CORE,
            RolePermission::P_CAN_CREATE_APPLICATION,
        ],
        RolePermission::ROLE_AGENCY_OFFICE_ALLOCATOR => [
            RolePermission::P_AGENCY_AGENT_OFFICE_ALLOCATOR_CORE,

        ],
        RolePermission::ROLE_AGENCY_OFFICE_ADMIN => [
            RolePermission::P_AGENCY_OFFICE_ADMIN,
        ],

        RolePermission::ROLE_AGENCY_OFFICE_DIRECTOR => [
            RolePermission::P_AGENCY_OFFICE_DIRECTOR,
        ],

        RolePermission::ROLE_AGENCY_OFFICE_PROPERTY_MANAGER => [
            RolePermission::P_AGENCY_OFFICE_PROPERTY_MANAGER,
        ],

        RolePermission::ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER => [
            RolePermission::P_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER,
        ],

        RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT => [
            RolePermission::P_AGENCY_OFFICE_REAL_ESTATE_AGENT,
            RolePermission::P_CAN_CREATE_APPLICATION,
        ],

        RolePermission::ROLE_HOOD_CHATBOT_USER => [
            RolePermission::P_HOOD_CHATBOT_USER_CORE,
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        self::createRolePermission(RolePermissionSeeder::ROLES, RolePermissionSeeder::PERMISSIONS);
    }

    public static function createRolePermission($roles, $permissionsOfRoles)
    {
        foreach ($roles as $role) {
            $r = Role::findOrCreate($role);
            if (array_key_exists($role, $permissionsOfRoles)) {
                foreach ($permissionsOfRoles[$role] as $permission) {
                    $p = Permission::findOrCreate($permission);
                    $p->assignRole($r);
                }
            }
        }
    }
}
