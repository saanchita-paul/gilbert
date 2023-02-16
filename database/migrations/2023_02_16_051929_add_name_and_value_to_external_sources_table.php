<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameAndValueToExternalSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_sources', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('name', 50)->after('password');
            $table->string('logo')->nullable()->after('name');
            $table->unsignedInteger('source_id')->nullable()->after('source_type');
            $table->string('table_name', 50)->nullable()->after('source_id');
            $table->tinyInteger('order')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_sources', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('logo');
            $table->dropColumn('source_id');
            $table->dropColumn('table_name');
            $table->dropColumn('order');
        });
    }
}
