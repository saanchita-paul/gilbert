<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLifeSupportEquipmentIdToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('life_support_equipment_id')->nullable()->after('is_power_life_support');
            $table->text('medical_reason')->nullable()->after('life_support_equipment_id');
            $table->foreign('life_support_equipment_id')->references('id')->on('life_support_equipment')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->dropColumn([
                'life_support_equipment_id',
                'medical_reason'
            ]);
        });
    }
}
