<?php

namespace App\Services;

use App\Services\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RolePermissionService;

class ExternalAdminMigrationService
{
    protected array $roles = [
        #Hood External Admin
        RolePermission::ROLE_EXTERNAL_ADMIN,
    ];

    protected array $permissions = [
        #Hood External Permissions
        RolePermission::ROLE_EXTERNAL_ADMIN => [
            RolePermission::P_HOOD_EXTERNAL_ADMIN_CORE,
            RolePermissionService::CAN_GET_OPERATION_REPORT,
            RolePermissionService::CAN_GET_EXPORT_REPORT,
            RolePermissionService::CAN_GET_REPORT_ACCESS_TOKEN,
            RolePermission::P_ACCESS_SALES_DASHBOARD
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

    public function down()
    {
        foreach ($this->roles as $role) {
            $r = Role::whereName($role)->first();
            foreach ($this->permissions[$role] as $permission) {
                $r->revokePermissionTo($permission);
            }
            $r->delete();
        }
    }
}
