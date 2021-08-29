<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')
                ->references('id')
                ->on('offices')
                ->onDelete('cascade');
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
                ->onDelete('cascade');
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
//            $table->string('email', 100)->nullable();
            $table->string('f_id_12', 45)->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('profile_photo', 100)->nullable();
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
        Schema::dropIfExists('agent_profiles');
    }
}
