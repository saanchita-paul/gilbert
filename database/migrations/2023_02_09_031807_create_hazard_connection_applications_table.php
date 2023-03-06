<?php

use App\Models\ConnectionApplication;
use App\Models\Hazard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHazardConnectionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hazard_connection_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hazard::class)
                ->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ConnectionApplication::class)
                ->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hazard_connection_applications');
    }
}
