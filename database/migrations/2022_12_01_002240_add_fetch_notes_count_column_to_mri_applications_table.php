<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFetchNotesCountColumnToMriApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mri_applications', function (Blueprint $table) {
            $table->integer('fetch_notes_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mri_applications', function (Blueprint $table) {
            $table->dropColumn('fetch_notes_count');
        });
    }
}
