<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualStatusChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_status_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_application_id')->nullable()
                ->constrained('connection_applications', 'id')->nullOnDelete();
            $table->foreignId('changed_by')->nullable()
                ->constrained('users', 'id')->nullOnDelete();
            $table->string('title');
            $table->string('status_change_reason')->nullable();
            $table->text('data')->nullable();
            $table->string('user_role', 45);
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
        Schema::dropIfExists('manual_status_change_logs');
    }
}
