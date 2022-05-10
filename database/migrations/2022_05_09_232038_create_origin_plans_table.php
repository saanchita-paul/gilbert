<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOriginPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('origin_plans', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 255);
            $table->string('product_code', 20);
            $table->string('campaign_id', 20);
            $table->string('description');
            $table->string('division_id', 10);
            $table->string('customer_type_id', 50);
            $table->string('status', 10);
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
        Schema::dropIfExists('origin_plans');
    }
}
