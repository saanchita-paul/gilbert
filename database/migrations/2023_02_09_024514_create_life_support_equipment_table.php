<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeSupportEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('life_support_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('powershop_value')->nullable();
            $table->string('is_active')->default(true);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'LifeSupportEquipmentSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('life_support_equipment');
    }
}
