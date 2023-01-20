<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMriOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mri_offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("office_id")->nullable();
            $table->string('application_id', 255)->nullable();
            $table->string('key', 255)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->timestamp('activation_date')->nullable();
            $table->timestamps();

            $table->foreign('office_id')
                ->on('offices')
                ->references('id')
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
        Schema::dropIfExists('mri_offices');
    }
}
