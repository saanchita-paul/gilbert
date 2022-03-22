<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsaCallHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tsa_call_history', function (Blueprint $table) {
            $table->id();
            $table->json("all_fields_dump")->nullable();
            $table->unsignedBigInteger("connection_application_id")->nullable();
            $table->foreign('connection_application_id')
                ->on('connection_applications')
                ->references('id')
                ->onDelete('cascade');
            
            $table->string("num_attempts")->nullable();
            $table->string("lead_status")->nullable();
            $table->string("attempts_outcome")->nullable();
            $table->string("attempts_disposition_code")->nullable();
            $table->string("attempts_disposition_sub_code")->nullable();
            $table->string("tsa_id")->nullable();
            
            $table->string("attempts_id")->nullable();
            $table->dateTime("attempts_assigned_timestamp")->nullable();
            $table->dateTime("attempts_initiated_timestamp")->nullable();
            $table->dateTime("attempts_connected_timestamp")->nullable();
            $table->dateTime("attempts_disconnected_timestamp")->nullable();
            $table->dateTime("attempts_disposed_timestamp")->nullable();


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
        Schema::dropIfExists('tsa_call_history');
    }
}
