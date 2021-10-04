<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->string('unit_number',45)->after('supplier')->nullable();
            $table->string('street_number',45)->after('street_address')->nullable();
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
            $table->dropColumn('unit_number');
            $table->dropColumn('street_number');
        });
    }
}
