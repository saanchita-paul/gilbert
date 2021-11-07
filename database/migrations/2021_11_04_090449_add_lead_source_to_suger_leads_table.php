<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadSourceToSugerLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suger_leads', function (Blueprint $table) {
            //
            $table->string('foxie_lead_source')->nullable();
            $table->text('foxie_lead_source_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suger_leads', function (Blueprint $table) {
            //
            $table->dropColumn(['foxie_lead_source' , 'foxie_lead_source_description']);
        });
    }
}
