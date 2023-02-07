<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedAtToConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('connection_applications', 'assigned_at')) {
            Schema::table('connection_applications', function (Blueprint $table) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_to');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('connection_applications', 'assigned_at')) {
            Schema::table('connection_applications', function (Blueprint $table) {
                $table->dropColumn(['assigned_at']);
            });
        }
    }
}
