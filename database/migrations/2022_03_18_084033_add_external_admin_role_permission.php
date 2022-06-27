<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\ExternalAdminMigrationService;

class AddExternalAdminRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new ExternalAdminMigrationService())->seed();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        (new ExternalAdminMigrationService())->down();
    }
}
