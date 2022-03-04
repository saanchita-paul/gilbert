<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\CreateApplicationMigrationService;

class AddCreateApplicationPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $service = new CreateApplicationMigrationService();
        $service->setPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $service = new CreateApplicationMigrationService();
        $service->revokePermissions();
    }
}
