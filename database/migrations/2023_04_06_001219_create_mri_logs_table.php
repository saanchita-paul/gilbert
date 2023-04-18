<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $mriTables = [
        'mri_agents',
        'mri_applications',
        'mri_notes',
        'mri_offices',
        'mri_properties'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mri_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('url');
            $table->json('request_header')->nullable();
            $table->json('request_query')->nullable();
            $table->json('request_body')->nullable();
            $table->integer('status_code')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamps();
        });

        foreach ($this->mriTables as $val) {
            Schema::table($val, function (Blueprint $table) {
                $table->unsignedBigInteger('mri_log_id')->nullable();
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
        Schema::dropIfExists('mri_logs');

        foreach ($this->mriTables as $val) {
            Schema::table($val, function (Blueprint $table) {
                $table->dropColumn('mri_log_id');
            });
        }
    }
};
