<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToTsaCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tsa_call_histories', function (Blueprint $table) {
            $table->index('attempt_id');
        });

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
        Schema::table('tsa_call_histories', function (Blueprint $table) {
            $table->dropIndex('tsa_call_histories_attempt_id_index');
        });
    }
}
