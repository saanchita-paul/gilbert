<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHubspotHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubspot_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('contact_id');
            $table->integer('old_connection_application_id');
            $table->integer('new_connection_application_id');
            $table->string('email', 200);
            $table->string('address_as_text', 500);
            $table->json('hubspot_response');
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
        Schema::dropIfExists('hubspot_histories');
    }
}
