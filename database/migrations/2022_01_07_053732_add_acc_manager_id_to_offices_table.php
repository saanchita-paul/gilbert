<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccManagerIdToOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('hood_agent_id')->nullable();
            $table->foreign('hood_agent_id')
                ->references('id')
                ->on('agent_profiles')
                ->onDelete('cascade');
            $table->integer('rent_roll')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            //
            $table->dropColumn(["hood_agent_id" , "rent_roll" ]);
        });
    }
}
