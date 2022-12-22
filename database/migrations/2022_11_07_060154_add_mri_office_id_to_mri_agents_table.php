<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMriOfficeIdToMriAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mri_agents', function (Blueprint $table) {
            $table->unsignedBigInteger("mri_office_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mri_agents', function (Blueprint $table) {
            $table->dropColumn('mri_office_id');
        });
    }
}
