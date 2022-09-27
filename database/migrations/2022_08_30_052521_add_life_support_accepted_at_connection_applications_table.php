<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLifeSupportAcceptedAtConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->renameColumn('life_support_accepted_at', 'power_life_support_accepted_at');
            $table->dateTime("gas_life_support_accepted_at")->nullable();
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
            $table->renameColumn('power_life_support_accepted_at', 'life_support_accepted_at');
            $table->dropColumn('gas_life_support_accepted_at');
        });
    }
}
