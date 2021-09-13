<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingAddressToConnectionApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->string('billing_unit_number',45)->nullable();
            $table->string('billing_street_number',45)->nullable();
            $table->string('billing_street_name', 70)->nullable();
            $table->string('billing_address_text', 150)->nullable();
            $table->string('billing_address_unit', 45)->nullable();
            $table->string('billing_street_address', 100)->nullable();
            $table->string('billing_city', 45)->nullable();
            $table->string('billing_state', 45)->nullable();
            $table->string('billing_postcode', 10)->nullable();
            $table->boolean('is_billing_same')->nullable();
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
            $table->dropColumn('billing_unit_number');
            $table->dropColumn('billing_street_number');
            $table->dropColumn('billing_street_name');
            $table->dropColumn('billing_address_text');
            $table->dropColumn('billing_address_unit');
            $table->dropColumn('billing_street_address');
            $table->dropColumn('billing_city');
            $table->dropColumn('billing_state', 45);
            $table->dropColumn('billing_postcode');
            $table->boolean('is_billing_same')->nullable();
        });
    }
}
