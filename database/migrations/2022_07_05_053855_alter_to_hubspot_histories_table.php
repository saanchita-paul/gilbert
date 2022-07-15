<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterToHubspotHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubspot_histories', function (Blueprint $table) {
            $table->integer('contact_id')->nullable()->change();
            $table->integer('connection_application_id')->nullable()->change();
            $table->string('email', 200)->nullable()->change();
            $table->string('address_as_text', 500)->nullable()->change();
            $table->json('hubspot_response')->nullable()->change();
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
            $table->integer('contact_id')->change();
            $table->integer('connection_application_id')->change();
            $table->string('email', 200)->change();
            $table->string('address_as_text', 500)->change();
            $table->json('hubspot_response')->change();
        });
    }
}
