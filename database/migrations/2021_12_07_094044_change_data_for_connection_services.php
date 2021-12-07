<?php

use App\Models\ConnectionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataForConnectionServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ConnectionService::query()->whereHas('connectionApplication', function (Builder $cp) {
            $cp->whereNotNull('vendor_id');
        })->where('status', ConnectionService::STATUS_EA_PROCESSINF)
            ->update(['status' => ConnectionService::STATUS_EA_SUBMIT]);
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
