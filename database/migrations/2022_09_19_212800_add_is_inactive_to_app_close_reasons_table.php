<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsInactiveToAppCloseReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('app_close_reasons', 'deleted_at')){
            Schema::table('app_close_reasons', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
        Schema::table('app_close_reasons', function (Blueprint $table) {
            $table->boolean('is_inactive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_close_reasons', function (Blueprint $table) {
            $table->dropColumn('is_inactive');
        });
    }
}
