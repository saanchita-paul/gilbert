<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSugerLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suger_leads', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->json("all_fields_dump")->nullable();
            $table->unsignedBigInteger('connection_application_id')->nullable();
            $table->foreign('connection_application_id')
                ->references('id')
                ->on('connection_applications')
                ->onDelete('cascade');
            $table->dateTime('updated')->nullable();
            $table->dateTime('created')->nullable();
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
        Schema::dropIfExists('suger_leads');
    }
}