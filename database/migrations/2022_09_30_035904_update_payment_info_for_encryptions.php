<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentInfoForEncryptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('powershop_payment_infos', function (Blueprint $table) {
            $table->text('px_callback_result')->nullable()->change();
            $table->string('px_card_type', 1000)->nullable()->change();
            $table->string('px_card_number', 1000)->nullable()->change();
            $table->string('px_card_expire_date', 1000)->nullable()->change();
            $table->string('px_card_holder_name', 1000)->nullable()->change();
            $table->string('px_dps_billing_id', 1000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //no need
    }
}
