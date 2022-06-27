<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAddressCompleteStateShortToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            //
            $table->tinyInteger("is_address_complete")->nullable();
            $table->tinyInteger("billing_is_address_complete")->nullable();
            $table->string("state_short")->nullable();
            $table->string("billing_state_short")->nullable();
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
            //
            $table->dropColumn(["is_address_complete", "state_short","billing_is_address_complete", "billing_state_short"]);
        });
    }
}
