<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropJsonColumnFromTsaCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('tsa_call_histories', 'all_fields_dump')) {
            Schema::table('tsa_call_histories', function (Blueprint $table) {
                $table->dropColumn('all_fields_dump');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //no need to reverse
    }
}
