<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUrlColumnIncreaseSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_logs', function (Blueprint $table) {
            $table->string('url', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_logs', function (Blueprint $table) {
            $table->string('url', 200)->change();
        });
    }
}
