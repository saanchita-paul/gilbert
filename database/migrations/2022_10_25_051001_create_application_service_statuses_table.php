<?php

use App\Models\ApplicationServiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationServiceStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_service_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default(ApplicationServiceStatus::TYPE_SERVICE);
            $table->string('display_text');
            $table->string('display_text_alias')->nullable();
            $table->integer('status_value');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('application_service_statuses');
    }
}
