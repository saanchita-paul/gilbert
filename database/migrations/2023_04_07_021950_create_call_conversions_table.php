<?php

use App\Models\ConnectionApplication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_application_id');
            $table->foreign('connection_application_id')
                ->on('connection_applications')
                ->references('id')
                ->onDelete('cascade');

            $table->string('caller_id')->nullable();
            $table->dateTime('call_start_at')->nullable();
            $table->dateTime('call_end_at')->nullable();
            $table->dateTime('conversation_at')->nullable();
            $table->dateTime('uploaded_at')->nullable();
            $table->string('status')->nullable();
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('call_conversions');
    }
}
