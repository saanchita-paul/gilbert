<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRejectionCodeColumnIncreaseSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rejection_reasons', function (Blueprint $table) {
            $table->string('reason_code', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rejection_reasons', function (Blueprint $table) {
            $table->string('reason_code', 50)->nullable()->change();
        });
    }
}
