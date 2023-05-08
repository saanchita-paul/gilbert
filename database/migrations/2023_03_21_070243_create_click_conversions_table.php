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
            $table->string('gcl_id')->nullable()->index('gcl_index');
            $table->dateTime('conversion_date')->nullable();
            $table->dateTime('last_checked')->nullable();
            $table->tinyInteger('should_skip')->nullable();
            $table->dateTime('uploaded_at')->nullable();
            $table->string('status')->nullable();
            $table->text('reason')->nullable();
            $table->tinyInteger('generated_from_creation')->nullable();
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
