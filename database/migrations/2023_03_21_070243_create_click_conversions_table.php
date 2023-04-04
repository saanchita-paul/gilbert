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
        Schema::dropIfExists('click_conversions');
        Schema::create('click_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_application_id')
                ->constrained('connection_applications')
                ->onDelete('cascade');
            $table->string('gcl_id')->nullable(true);
            $table->dateTime('last_checked')->nullable(true);
            $table->tinyInteger('should_skip')->nullable(false)->default(0);
            $table->tinyInteger('is_uploaded_click')->nullable(false)->default(0);
            $table->tinyInteger('generated_from_creation')->nullable(false)->default(1);
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
        Schema::dropIfExists('click_conversions');
    }
};
