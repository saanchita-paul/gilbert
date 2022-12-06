<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetServiceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internet_service_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_application_id')
                ->constrained('connection_applications')->cascadeOnDelete();
            $table->foreignId('connection_service_id')->nullable()
                ->comment('It is helpful for reporting purpose')
                ->constrained('connection_services')->cascadeOnDelete();
            $table->boolean('is_shipping_same')->default(true);
            $table->string('unit_number')->nullable();
            $table->string('street_number')->nullable();
            $table->string('street_name_only')->nullable();
            $table->string('address_text')->nullable();
            $table->string('street_address')->nullable();
            $table->string('street_type')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('state')->nullable();
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
        Schema::dropIfExists('internet_service_infos');
    }
}
