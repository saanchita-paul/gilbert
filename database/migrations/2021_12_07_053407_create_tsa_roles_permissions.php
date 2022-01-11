<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\TSARolePermissionService;

class CreateTsaRolesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new TSARolePermissionService())->seed();
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
