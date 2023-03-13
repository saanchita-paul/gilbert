<?php

use App\Services\HazardService;
use Illuminate\Database\Migrations\Migration;

class AddMigrateOldHazardDataToHazardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        HazardService::migrateOldData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
