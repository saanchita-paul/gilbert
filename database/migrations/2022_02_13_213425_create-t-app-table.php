<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_app', function (Blueprint $table) {
            $table->id();
            $table->json("all_fields_dump")->nullable();
            $table->unsignedBigInteger("connection_application_id")->nullable();
            $table->foreign('connection_application_id')
                ->on('connection_applications')
                ->references('id')
                ->onDelete('cascade');
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
        Schema::dropIfExists('t_app');
    }
}
