<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHubspotContactIdToConnectionApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->string('hubspot_contact_id',20)->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->dropColumn('hubspot_contact_id');
        });
    }
}
