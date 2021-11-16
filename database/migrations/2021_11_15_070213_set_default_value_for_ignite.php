<?php

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Doctrine\DBAL\Driver\IBMDB2\Connection;
use Illuminate\Database\Migrations\Migration;

class SetDefaultValueForIgnite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        ConnectionApplication::where('source' , ConnectionApplication::SOURCE_IGNITE)
        ->update(['tenancy_type' => ConnectionApplication::TENANCY_TYPE_RENTER]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
