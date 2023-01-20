<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDayMakeNullableToOfficeAutoAssignTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_auto_assign_time_slots', function (Blueprint $table) {
            $table->string('day')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_auto_assign_time_slots', function (Blueprint $table) {
            $table->string('day')->change();
        });
    }
}
