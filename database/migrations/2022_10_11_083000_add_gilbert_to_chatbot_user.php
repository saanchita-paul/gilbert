<?php

use App\Services\RolePermission;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGilbertToChatbotUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = [RolePermission::ROLE_HOOD_CHATBOT_USER];
        $permission = [
            RolePermission::ROLE_HOOD_CHATBOT_USER => [
                RolePermission::P_HOOD_CHATBOT_USER_CORE,
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
        Schema::table('chatbot_user', function (Blueprint $table) {
            //
        });
    }
}
