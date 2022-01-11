<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanToApplicationNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_notes', function (Blueprint $table) {
            $table->json("connection_details")->nullable();
            $table->json("plan_details")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_notes', function (Blueprint $table) {
            $table->dropColumn("connection_details");
            $table->dropColumn("plan_details");
        });
    }
}
