<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalPowershopFieldsToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
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
            $table->dropColumn('life_support_accepted_at');
            $table->dropColumn('terms_and_conditions_accepted_at');
            $table->dropColumn('eligible_for_concessions');
            $table->dropColumn('promotion_code');
            $table->dropColumn('promotion_terms_and_conditions_accepted_at');
        });
    }
}
