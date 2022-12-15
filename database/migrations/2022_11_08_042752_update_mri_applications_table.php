<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMriApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mri_applications');

        Schema::create('mri_applications', function (Blueprint $table) {
            $table->id();
            $table->string('tenancy_id')->unique();
            $table->unsignedBigInteger("mri_office_id");
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email_address')->nullable();
            $table->string('mobile_phone_number')->nullable();
            $table->string('home_number')->nullable();
            $table->string('property')->nullable();
            $table->double('rent_amount')->nullable();
            $table->string('rent_period')->nullable();
            $table->boolean('prospect')->default(false);
            $table->boolean('lease_detail_charge_tenants_water_usage')->default(false);
            $table->timestamp('lease_start_date')->nullable();
            $table->timestamp('lease_end_date')->nullable();
            $table->timestamp('original_lease_start_date')->nullable();
            $table->timestamp('vacate_date')->nullable();
            $table->string('authorized_title')->nullable();
            $table->string('authorized_first_name')->nullable();
            $table->string('authorized_last_name')->nullable();
            $table->string('authorized_email_address')->nullable();
            $table->string('authorized_mobile_phone_number')->nullable();
            $table->string('authorized_home_number')->nullable();
            $table->boolean('is_marketing')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_archived')->default(false);
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
