<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('connection_applications', function (Blueprint $table) {
            //
            $table->string('middle_name', 45)->nullable()->after('first_name');
            $table->boolean('has_electricity')->nullable();
            $table->string('land_number' , 20)->nullable();
            $table->string('inspection_time' , 100)->nullable();
            $table->tinyInteger('family_violation')->nullable();

            $table->unsignedBigInteger('application_secondary_acc_id');
            $table->foreign('application_secondary_acc_id')
            ->references('id')
            ->on('application_secondary_acc')
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
        //
    }
}
