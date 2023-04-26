<?php

use App\Models\GoodtelPlan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanIdToInternetServiceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internet_service_infos', function (Blueprint $table) {
            $table->foreignIdFor(GoodtelPlan::class)->nullable()
                ->after('connection_service_id')
                ->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internet_service_infos', function (Blueprint $table) {
            //
        });
    }
}
