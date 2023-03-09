<?php

use Database\Seeders\GoodtelPlanSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodtelPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goodtel_plans', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->string('name')->unique();
            $table->string('type');
            $table->double('price');
            $table->string('details_url');
            $table->string('mbps')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('goodtel_plans');
    }
}
