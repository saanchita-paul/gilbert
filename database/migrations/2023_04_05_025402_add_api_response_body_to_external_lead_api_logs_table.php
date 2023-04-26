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
        Schema::table('external_lead_api_logs', function (Blueprint $table) {
            $table->json('api_response_body')->nullable();
            $table->renameColumn('all_fields_dump', 'api_request_body');
            $table->integer('status_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_lead_api_logs', function (Blueprint $table) {
            //
        });
    }
};
