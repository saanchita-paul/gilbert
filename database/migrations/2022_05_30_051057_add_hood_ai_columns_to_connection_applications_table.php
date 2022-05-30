<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoodAiColumnsToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->renameColumn('utm_source', 'hood_utm_source');
            $table->string("hood_utm_content", 300)->nullable();
            $table->string("hood_utm_medium", 100)->nullable();
            $table->string("hood_hss_channel", 200)->nullable();
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
            $table->renameColumn('hood_utm_source', 'utm_source');
            $table->dropColumn('hood_utm_content');
            $table->dropColumn('hood_utm_medium');
            $table->dropColumn('hood_hss_channel');
        });
    }
}
