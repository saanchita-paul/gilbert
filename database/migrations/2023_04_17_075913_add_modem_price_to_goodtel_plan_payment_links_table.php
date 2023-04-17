<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModemPriceToGoodtelPlanPaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goodtel_plan_payment_links', function (Blueprint $table) {
            $table->double('modem_price')->nullable();
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
            $table->dropColumn('modem_price');
        });
    }
}
