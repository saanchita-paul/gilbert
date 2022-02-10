<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoodLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hood_leads', function (Blueprint $table) {
            $table->id();
            $table->json('all_fields_dump')->nullable();
            $table->unsignedBigInteger('connection_application_id')->nullable();
            $table->foreign('connection_application_id')
                ->on('connection_applications')
                ->references('id')
                ->onDelete('cascade');
            $table->string("lead_id", 200)->nullable();
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
        Schema::dropIfExists('hood_leads');
    }
}
