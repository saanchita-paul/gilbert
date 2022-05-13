<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

class ChangeUnassignedStatusCodeOfIgniteLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $igniteConnectionApplications = ConnectionApplication::where('source', ConnectionApplication::SOURCE_IGNITE)
            ->pluck('id')
            ->toArray();

        ConnectionService::whereIn('connection_application_id', $igniteConnectionApplications)
            ->where('status', ConnectionService::STATUS_UNASSIGNED)
            ->update(['status' => ConnectionService::STATUS_EA_PROCESSINF]);
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
