<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDuplicateToConnectionApplicaiton extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->boolean('is_duplicate')->nullable();
            $table->string('duplication_group_id')->nullable();
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
            $table->dropColumn('is_duplicate');
            $table->dropColumn('duplication_group_id');
        });
    }
}
