<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginColumnsToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->boolean('is_email_marketing')->nullable();
            $table->boolean('is_access_require')->nullable();
            $table->boolean('is_gas_life_support')->nullable();
            $table->boolean('is_any_unrestrained_animal')->nullable();
            $table->string('concession_card_type', 50)->nullable();
            $table->string('concession_card_number', 50)->nullable();
            $table->date('concession_start_date')->nullable();
            $table->date('concession_end_date')->nullable();
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
            $table->dropColumn('is_email_marketing');
            $table->dropColumn('is_access_require');
            $table->dropColumn('is_gas_life_support');
            $table->dropColumn('is_any_unrestrained_animal');
            $table->dropColumn('concession_card_type');
            $table->dropColumn('concession_card_number');
            $table->dropColumn('concession_start_date');
            $table->dropColumn('concession_end_date');
        });
    }
}
