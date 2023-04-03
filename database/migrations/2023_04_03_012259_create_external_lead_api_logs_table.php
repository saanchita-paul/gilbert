<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_lead_api_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_source_id');
            $table->foreign('external_source_id')
                ->on('external_sources')
                ->references('id')
                ->onDelete('cascade');
            $table->json('all_fields_dump');
            $table->unsignedBigInteger('connection_application_id')->nullable();
            $table->foreign('connection_application_id')
                ->on('connection_applications')
                ->references('id')
                ->onDelete('cascade');
            $table->string('lead_id')->nullable();
            $table->string('agency_name')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('agent_email')->nullable();
            $table->json('exception_log')->nullable();
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
        Schema::dropIfExists('external_lead_api_logs');
    }
};
