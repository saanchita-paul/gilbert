<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLotIdToPropertyMeLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_me_leads', function (Blueprint $table) {
            $table->string('lot_id', 150)->after('lead_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_me_leads', function (Blueprint $table) {
            $table->dropColumn('lot_id');
        });
    }
}
