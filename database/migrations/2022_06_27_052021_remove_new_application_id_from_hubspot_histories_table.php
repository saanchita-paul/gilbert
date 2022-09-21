<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNewApplicationIdFromHubspotHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubspot_histories', function (Blueprint $table) {
            $table->dropColumn('new_connection_application_id');
            $table->renameColumn('old_connection_application_id', 'connection_application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubspot_histories', function (Blueprint $table) {
            $table->renameColumn('connection_application_id', 'old_connection_application_id');
            $table->integer('new_connection_application_id');
        });
    }
}
