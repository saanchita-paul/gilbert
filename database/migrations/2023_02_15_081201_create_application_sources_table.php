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
            $table->string('name', 100);
            $table->string('value', 100)->nullable();
            $table->string('logo', 255)->nullable();
            $table->integer('app_source_id')->nullable();
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
