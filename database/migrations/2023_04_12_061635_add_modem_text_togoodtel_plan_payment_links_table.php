<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModemTextTogoodtelPlanPaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goodtel_plan_payment_links', function (Blueprint $table) {
            $table->string('modem_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goodtel_plan_payment_links', function (Blueprint $table) {
            $table->dropColumn('modem_text');
        });
    }
}
