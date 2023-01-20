<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreferredPhoneNumberColumnToMriApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mri_applications', function (Blueprint $table) {
            $table->string('preferred_phone_number')->nullable();
            $table->string('authorized_preferred_phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mri_applications', function (Blueprint $table) {
            $table->dropColumn('preferred_phone_number');
            $table->dropColumn('authorized_preferred_phone_number');
        });
    }
}
