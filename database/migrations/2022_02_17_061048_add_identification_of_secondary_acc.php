<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentificationOfSecondaryAcc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_secondary_acc', function (Blueprint $table) {
            $table->tinyInteger('identification_type')->nullable();
            $table->string('card_number', 45)->nullable();
            $table->string('state', 70)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('card_color', 45)->nullable();
            $table->string('special_number', 45)->nullable();
            $table->date('expire_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_secondary_acc', function (Blueprint $table) {
            $table->dropColumn('identification_type');
            $table->dropColumn('card_number');
            $table->dropColumn('state');
            $table->dropColumn('country');
            $table->dropColumn('card_color');
            $table->dropColumn('special_number');
            $table->dropColumn('expire_date');
        });
    }
}
