<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationSecondaryAcc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_secondary_acc', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->tinyInteger('role')->nullable();
            $table->timestamp('dob')->nullable();
            $table->unsignedBigInteger('connection_application_id');
            $table->foreign('connection_application_id')
                ->references('id')
                ->on('connection_applications')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_secondary_acc');
    }
}
