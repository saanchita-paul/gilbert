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
            $table->boolean('has_electricity')->default('1')->nullable();
            $table->string('inspection_time' , 100)->nullable();
            $table->tinyInteger('phone_type')->default('1')->nullable()->after('phone');
            $table->tinyInteger('family_violance')->default('3')->nullable();
            $table->unsignedBigInteger('application_secondary_acc_id')->nullable();
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
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->dropColumn(['middle_name' , 'has_electricity' , 'land_number' , 'inspection_time' , 'family_violance' , 'application_secondary_acc_id' , 'application_secondary_acc_id']);
        });
    }
}
