<?php

use App\Models\EAClientCredential;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEAClientCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ea_client_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('vendor_code');
            $table->timestamps();
        });

        $this->addDefaultCredential();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ea_client_credentials');
    }


    private function addDefaultCredential()
    {
        $cc = new EAClientCredential();
        $cc->client_id = config('ea.default_client_id');
        $cc->client_secret = config('ea.default_client_secret');
        $cc->vendor_code = config('ea.default_vendor_code', 'HD2');
        $cc->name = 'default';
        $cc->save();
    }
}
