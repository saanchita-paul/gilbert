<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('connection_application_id');
            $table->foreign('connection_application_id')
                ->references('id')
                ->on('connection_applications')
                ->onDelete('cascade');
            $table->string('service_type', 45)->nullable();
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
        Schema::dropIfExists('connection_services');
    }
}
