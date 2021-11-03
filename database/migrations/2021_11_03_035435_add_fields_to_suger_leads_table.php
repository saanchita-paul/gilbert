<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSugerLeadsTable extends Migration
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
            $table->string('agent_name')->nullable();
            $table->string('agency_id')->nullable();
            $table->string('lead_id')->nullable();
            $table->string('agency_name')->nullable();
            $table->text('service_address')->nullable();
            $table->string('office_branch')->nullable();
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
            $table->dropColumn(['lead_id' , 'agency_id', 'agent_name' , 'agency_name' , 'service_address' , 'office_branch']);
        });
    }
}
