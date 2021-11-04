<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnOfSugerLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('suger_leads', function(Blueprint $table) {
            $table->renameColumn('created', 'foxie_date_entered');
            $table->renameColumn('updated', 'foxie_date_modified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('suger_leads', function(Blueprint $table) {
            $table->renameColumn('foxie_date_entered', 'created');
            $table->renameColumn('foxie_date_modified', 'updated');
        });
    }
}
