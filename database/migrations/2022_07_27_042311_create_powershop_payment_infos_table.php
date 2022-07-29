<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowershopPaymentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('powershop_payment_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_application_id');
            $table->foreign('connection_application_id')
                ->references('id')
                ->on('connection_applications')
                ->onDelete('cascade');
            $table->tinyInteger('status')->nullable();
            $table->string('invite_token')->nullable();
            $table->boolean('is_active')->nullable();
            $table->integer("estimated_elec_billing_cost")->nullable();
            $table->string("estimated_elec_billing_period")->nullable();
            $table->integer("estimated_gas_billing_cost")->nullable();
            $table->string("estimated_gas_billing_period")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('powershop_payment_infos');
    }
}
