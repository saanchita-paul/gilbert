<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPowershopFieldsToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->integer("estimated_elec_billing_cost")->nullable();
            $table->string("estimated_elec_billing_period")->nullable();
            $table->integer("estimated_gas_billing_cost")->nullable();
            $table->string("estimated_gas_billing_period")->nullable();
            $table->dateTime("life_support_accepted_at")->nullable();
            $table->dateTime("terms_and_conditions_accepted_at")->nullable();
            $table->boolean("eligible_for_concessions")->nullable();
            $table->string("promotion_code")->nullable(); 
            $table->dateTime("promotion_terms_and_conditions_accepted_at")->nullable();
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
            $table->dropColumn('estimated_elec_billing_cost');
            $table->dropColumn('estimated_elec_billing_period');
            $table->dropColumn('estimated_gas_billing_cost');
            $table->dropColumn('estimated_gas_billing_period');
            $table->dropColumn('life_support_accepted_at');
            $table->dropColumn('terms_and_conditions_accepted_at');
            $table->dropColumn('eligible_for_concessions');
            $table->dropColumn('promotion_code');
            $table->dropColumn('promotion_terms_and_conditions_accepted_at');
            
        });
    }
}
