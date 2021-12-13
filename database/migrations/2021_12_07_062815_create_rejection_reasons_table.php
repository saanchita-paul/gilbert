<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectionReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejection_reasons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('connection_service_id')->nullable();
            $table->foreign('connection_service_id')
                ->on('connection_services')
                ->references('id')
                ->onDelete('cascade');

            $table->unsignedBigInteger('connection_application_id')->nullable();
            $table->foreign('connection_application_id')
                ->on('connection_applications')
                ->references('id')
                ->onDelete('cascade');

            $table->string('service_type')->nullable();
            $table->string('reason_code', 50)->nullable();
            $table->text('reason_text')->nullable();
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
        Schema::dropIfExists('rejection_reasons');
    }
}
