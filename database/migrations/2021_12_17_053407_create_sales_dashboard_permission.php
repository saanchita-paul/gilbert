<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSalesDashboardPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = [
            RolePermission::ROLE_HOOD_ADMIN,
            RolePermission::ROLE_HOOD_AGENT,
            RolePermission::ROLE_HOOD_TEAM_LEAD,
            RolePermission::ROLE_HOOD_CUSTOMER_REP,
        ];
    
        $permissions = [
            RolePermission::ROLE_HOOD_ADMIN => [
                RolePermission::P_ACCESS_SALES_DASHBOARD,
            ],
            RolePermission::ROLE_HOOD_AGENT => [
                RolePermission::P_ACCESS_SALES_DASHBOARD,
            ],
            RolePermission::ROLE_HOOD_TEAM_LEAD => [
                RolePermission::P_ACCESS_SALES_DASHBOARD,
            ],
            RolePermission::ROLE_HOOD_CUSTOMER_REP => [
                RolePermission::P_ACCESS_SALES_DASHBOARD,
            ],
        ];

        foreach ($roles as $role) {
            $r = Role::findOrCreate($role);
            foreach ( $permissions[$role] as $permission) {
                $p = Permission::findOrCreate($permission);
                $p->assignRole($r);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
