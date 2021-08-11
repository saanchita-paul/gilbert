<?php

namespace Database\Seeders;

use App\Services\RolePermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    protected array $roles = [
        RolePermission::ROLE_HOOD_ADMIN,
        RolePermission::ROLE_AGENCY_AGENT,
        RolePermission::ROLE_AGENCY_TEAM_LEAD,
        RolePermission::ROLE_AGENCY_OFFICE_ALLOCATOR,
        RolePermission::ROLE_AGENCY_OFFICE_ADMIN,
        RolePermission::ROLE_AGENCY_OFFICE_DIRECTOR,
        RolePermission::ROLE_AGENCY_OFFICE_PROPERTY_MANAGER,
        RolePermission::ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER,
        RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT


    ];

    protected array $permissions = [
        RolePermission::ROLE_HOOD_ADMIN => [
            RolePermission::P_HOOD_ADMIN_CORE
        ],
        RolePermission::ROLE_AGENCY_AGENT => [
            RolePermission::P_AGENCY_AGENT_CORE,
            RolePermission::P_CAN_CREATE_APPLICATION,
        ],
        RolePermission::ROLE_AGENCY_TEAM_LEAD => [
            RolePermission::P_AGENCY_TEAM_LEAD_CORE
        ],
        RolePermission::ROLE_AGENCY_OFFICE_ALLOCATOR => [
            RolePermission::P_AGENCY_AGENT_OFFICE_ALLOCATOR_CORE
        ],
        RolePermission::ROLE_AGENCY_OFFICE_ADMIN => [
            RolePermission::P_AGENCY_OFFICE_ADMIN
        ],
        RolePermission::ROLE_AGENCY_OFFICE_DIRECTOR => [
            RolePermission::P_AGENCY_OFFICE_DIRECTOR
        ],
        RolePermission::ROLE_AGENCY_OFFICE_PROPERTY_MANAGER => [
            RolePermission::P_AGENCY_OFFICE_PROPERTY_MANAGER
        ],
        RolePermission::ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER => [
            RolePermission::P_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER
        ],
        RolePermission::ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT => [
            RolePermission::P_AGENCY_OFFICE_REAL_ESTATE_AGENT
        ],

    ];
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->roles as $role) {
            $r = Role::create(['name' => $role]);
            foreach ( $this->permissions[$role] as $permission) {
                $p = Permission::create(['name' => $permission]);
                $p->assignRole($r);
            }
        }
    }
}
