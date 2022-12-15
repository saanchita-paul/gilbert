<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasProcessNoteColumnToMriApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mri_applications', function (Blueprint $table) {
            $table->boolean('has_process_note')->default(false);
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
            $table->dropColumn('has_process_note');
        });
    }
}
