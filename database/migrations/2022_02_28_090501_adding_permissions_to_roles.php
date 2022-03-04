<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\RolePermissionMigrationService;

class AddingPermissionsToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $service = new RolePermissionMigrationService();
        $service->setPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $service = new RolePermissionMigrationService();
        $service->revokePermissions();
    }
}
