<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_property', function (Blueprint $table) {
            $table->id();
            $table->json("all_fields_dump")->nullable();
            $table->string("connection_application_id")->nullable();
            $table->string("lead_id")->nullable();
            $table->string("agency_name")->nullable();
            $table->string("agent_name")->nullable();
            $table->string("agent_email")->nullable();
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
        Schema::dropIfExists('our_property');
    }
}
