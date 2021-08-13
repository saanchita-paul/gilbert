<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')
                ->references('id')
                ->on('offices')
                ->onDelete('cascade');
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
                ->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('agent_profiles')
                ->onDelete('cascade');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')
                ->references('id')
                ->on('agent_profiles')
                ->onDelete('cascade');
            $table->string('first_name', 45)->nullable();
            $table->string('last_name', 45)->nullable();
            $table->string('email', 45)->nullable();
            $table->string('phone', 45)->nullable();
            $table->tinyInteger('tenancy_type')->nullable();
            $table->date('dob')->nullable();
            $table->dateTime('moving_date')->nullable();
            $table->string('address_unit', 45)->nullable();
            $table->string('street_address', 100)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('country', 200)->nullable();
            $table->text('additional_instruction')->nullable();
            $table->string('address_text', 150)->nullable();
            $table->tinyInteger('is_email_billing')->nullable();
            $table->tinyInteger('property_type')->nullable();
            $table->tinyInteger('has_life_support')->nullable();
            $table->tinyInteger('has_solar')->nullable();
            $table->string('nmi', 45)->nullable();
            $table->string('mirn', 45)->nullable();
            $table->tinyInteger('is_escalated')->nullable();
            $table->tinyInteger('supplier')->nullable();
            $table->tinyInteger('plan_type')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('connection_applications');
    }
}
