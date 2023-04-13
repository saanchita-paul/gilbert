<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCafPlanNameToGoodtelPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goodtel_plans', function (Blueprint $table) {
            $table->string('caf_plan_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goodtel_plans', function (Blueprint $table) {
            $table->dropColumn('caf_plan_name');
        });
    }
}
