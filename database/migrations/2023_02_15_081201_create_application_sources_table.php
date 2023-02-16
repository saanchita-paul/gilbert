<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('value', 50)->nullable();
            $table->string('logo')->nullable();
            $table->integer('source_id')->nullable();
            $table->string('table_name', 50)->nullable();
            $table->integer('order')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('application_sources');
    }
}
