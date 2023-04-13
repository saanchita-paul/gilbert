<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCafGeneratedToInternetServiceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internet_service_infos', function (Blueprint $table) {
            $table->boolean('is_caf_generated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internet_service_infos', function (Blueprint $table) {
            $table->dropColumn([
                'is_caf_generated'
            ]);
        });
    }
}
