<?php

use App\Services\RolePermission;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsChatbotOfficeToOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->boolean("is_chatbot_office")->default(false);
        });

        $role = [RolePermission::ROLE_HOOD_ADMIN];
        $permission = [
            RolePermission::ROLE_HOOD_ADMIN => [
                RolePermission::P_CAN_SWITCH_AUTO_CHATBOT_ASSIGN,
            ]
        ];
        RolePermissionSeeder::createRolePermission($role, $permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn('is_chatbot_office');
        });
    }
}
