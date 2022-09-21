<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppCloseReasonIdForeignToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('app_close_reason_id')->nullable()->after('water_submit_response');

            $table->foreign('app_close_reason_id')->references('id')->on('app_close_reasons')->onDelete('set null');
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
            $table->dropForeign(['app_close_reason_id']);
            $table->dropColumn('app_close_reason_id');
        });
    }
}
