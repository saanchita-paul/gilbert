<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToConnectionServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_services', function (Blueprint $table) {
            $table->integer('status')->nullable();
            $table->string('reason',250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_services', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('reason');
        });
    }
}
