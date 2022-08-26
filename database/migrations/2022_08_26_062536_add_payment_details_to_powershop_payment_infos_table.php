<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDetailsToPowershopPaymentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('powershop_payment_infos', function (Blueprint $table) {
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('customer_full_name', 100)->nullable();
            $table->string('customer_email', 100)->nullable();
            $table->string('customer_phone', 50)->nullable();
            $table->string('px_transaction_type', 25)->nullable();
            $table->decimal('px_amount')->nullable();
            $table->string('px_currency_type', 20)->nullable();
            $table->uuid('px_txn_id')->nullable();
            $table->tinyInteger('px_is_enable_billing')->nullable();
            $table->text('px_redirect_url')->nullable();
            $table->string('px_callback_result', 250)->nullable();
            $table->string('px_recurring_mode', 25)->nullable();
            $table->string('px_response_text', 50)->nullable();
            $table->string('px_card_type', 20)->nullable();
            $table->string('px_card_number', 50)->nullable();
            $table->string('px_card_expire_date', 20)->nullable();
            $table->string('px_card_holder_name', 100)->nullable();
            $table->string('px_dps_billing_id', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('powershop_payment_infos', function (Blueprint $table) {
            $table->dropColumn([
                'invited_at',
                'verified_at',
                'rejected_at',
                'customer_full_name',
                'customer_email',
                'customer_phone',
                'px_transaction_type',
                'px_amount',
                'px_currency_type',
                'px_txn_id',
                'px_is_enable_billing',
                'px_redirect_url',
                'px_recurring_mode',
                'px_callback_result',
                'px_response_text',
                'px_card_type',
                'px_card_number',
                'px_card_expire_date',
                'px_card_holder_name',
                'px_dps_billing_id'
            ]);
        });
    }
}
