<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuggestedNmiToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->string('suggested_nmi', 50)->after('nmi')->nullable();
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
            $table->dropColumn('suggested_nmi');
        });
    }
}
