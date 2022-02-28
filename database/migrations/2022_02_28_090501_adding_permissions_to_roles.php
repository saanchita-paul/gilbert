<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RolePermissionService;

class AddingPermissionsToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

        $agencyRoles = [
            $agency_agent,
            $agency_office_allocator,
            $agency_office_admin, 
            $agency_office_director, 
            $agency_office_property_manager,
            $agency_office_senior_property_manager,
            $agency_assistant_property_manager, 
            $agency_office_real_estate_agent, 
            $agency_office_business_development_manager,
            $agency_sales_pa, 
            $agency_receptionist, 
        ];
        

        $permissions = RolePermissionService::allPermission();
        foreach ($permissions as $permission) {
            $model = Permission::query()->firstOrNew([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
            $model->save();
        }

        if ($hood_admin) {
            $hood_admin->givePermissionTo(RolePermissionService::hoodAdminPermissions());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
}
