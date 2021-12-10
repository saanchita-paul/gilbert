<?php

namespace App\Services;

use App\Services\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TSARolePermissionService
{
    protected array $roles = [
        #Hood External Roles
        RolePermission::ROLE_EXTERNAL_HOOD_TEAM_LEAD,
        RolePermission::ROLE_EXTERNAL_HOOD_CUSTOMER_REP,
    ];

    protected array $permissions = [
        #Hood External Permissions
        RolePermission::ROLE_EXTERNAL_HOOD_TEAM_LEAD => [
            RolePermission::P_HOOD_EXTERNAL_TEAM_LEAD_CORE,
            RolePermission::P_HOOD_TEAM_LEAD_CORE,
            RolePermission::P_CAN_MANAGE_APPLICATION
        ],
        RolePermission::ROLE_EXTERNAL_HOOD_CUSTOMER_REP => [
            RolePermission::P_HOOD_EXTERNAL_CUSTOMER_REP_CORE,
            RolePermission::P_HOOD_CUSTOMER_REP_CORE,
            RolePermission::P_CAN_MANAGE_APPLICATION
        ],
    ];

    public function seed()
    {
        foreach ($this->roles as $role) {
            $r = Role::findOrCreate($role);
            foreach ( $this->permissions[$role] as $permission) {
                $p = Permission::findOrCreate($permission);
                $p->assignRole($r);
            }
        }
    }
}
