<?php
namespace App\Services;

use App\Services\RolePermission;
use Spatie\Permission\Models\Role;
use App\Services\RolePermissionService;

class AddPermissionToCsrRoleService
{
    public static function setPermissions()
    {
        $hood_customer_rep = Role::whereName(RolePermission::ROLE_HOOD_CUSTOMER_REP)->first();

        if ($hood_customer_rep) {
            $hood_customer_rep->givePermissionTo(RolePermissionService::hoodCustomerRepExtraPermissions());
        }
    }

    public static function revokePermissions()
    {
        $hood_customer_rep = Role::whereName(RolePermission::ROLE_HOOD_CUSTOMER_REP)->first();

        if ($hood_customer_rep) {
            $hood_customer_rep->revokePermissionTo(RolePermissionService::hoodCustomerRepExtraPermissions());
        }
    }
}
