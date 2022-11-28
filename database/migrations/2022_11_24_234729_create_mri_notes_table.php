<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMriNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mri_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mri_application_id');
            $table->string('note_id');
            $table->text('description');
            $table->string('entity_id');
            $table->string('entity_type');
            $table->string('category_id');
            $table->dateTime('last_modified');
            $table->string('last_modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mri_notes');
    }
}
