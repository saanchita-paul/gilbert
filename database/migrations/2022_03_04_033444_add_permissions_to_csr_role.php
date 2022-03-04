<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\AddPermissionToCsrRoleService;

class AddPermissionsToCsrRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $service = new AddPermissionToCsrRoleService();
        $service->setPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $service = new AddPermissionToCsrRoleService();
        $service->revokePermissions();
    }
}
