<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConnectionServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('connection_services', function (Blueprint $table) {
            $table->date('connection_date')->nullable();
            $table->text('provider_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('connection_services', function (Blueprint $table) {
            $table->dropColumn('connection_date');
            $table->dropColumn('provider_name');
        });
    }
}
