<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMriAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mri_agents', function (Blueprint $table) {
            $table->id();
            $table->string('agent_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_address');
            $table->string('mobile_phone_number');
            $table->string('roles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mri_agents');
    }
}
