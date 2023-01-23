<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mri_notes', function (Blueprint $table) {
            $table->boolean('is_checked')->default(false);
            $table->boolean('is_fetched')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mri_notes', function (Blueprint $table) {
            $table->dropColumn('is_checked');
            $table->dropColumn('is_fetched');
        });
    }
};
