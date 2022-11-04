<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMriApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mri_applications', function (Blueprint $table) {
            $table->id();
            $table->string('tenancy_id')->unique();
            $table->string('name');
            $table->string('property');
            $table->double('rent_amount')->nullable();
            $table->string('rent_period')->nullable();
            $table->boolean('prospect')->default(false);
            $table->boolean('lease_detail_charge_tenants_water_usage')->default(false);
            $table->timestamp('lease_start_date')->nullable();
            $table->timestamp('lease_end_date')->nullable();
            $table->timestamp('original_lease_start_date')->nullable();
            $table->timestamp('vacate_date')->nullable();
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
        Schema::dropIfExists('mri_applications');
    }
}
