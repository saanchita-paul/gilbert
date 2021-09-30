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
            $table->boolean('is_renovation_on')->nullable();
            $table->string('land_number' , 20)->nullable();
            $table->string('vendor_id' , 50)->nullable();
            $table->string('homephone', 45)->nullable();
            $table->tinyInteger('phone_type')->default('1')->nullable()->after('phone');
            $table->string('inspection_time' , 100)->nullable();
            $table->tinyInteger('family_violance')->nullable();

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
            $table->dropColumn(['middle_name' ,'homephone','is_renovation_on','phone_type', 'has_electricity' , 'land_number' , 'inspection_time' , 'family_violance' , 'application_secondary_acc_id' , 'application_secondary_acc_id']);
        });
    }
}
