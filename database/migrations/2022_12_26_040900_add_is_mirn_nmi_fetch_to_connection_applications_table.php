<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsMirnNmiFetchToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->boolean('loading_address_info')
                ->default(false);
            $table->boolean('is_embedded_nmi')
                ->default(false)
                ->comment('It replaced is embedded value or it get data from embedded');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->dropColumn([
                'loading_address_info',
                'is_embedded_nmi'
            ]);
        });
    }
}
