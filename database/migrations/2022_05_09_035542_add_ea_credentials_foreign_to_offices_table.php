<?php

use App\Models\EAClientCredential;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEaCredentialsForeignToOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        /** @var EAClientCredential | null $cc */
        $id = $this->getEaCredentialId();

        if(!$id) {
            throw new \Exception('No Default EA Credential not found');
        }
        Schema::table('offices', function (Blueprint $table) use ($id) {
            $table->unsignedBigInteger('ea_client_credential_id')->nullable()->default($id);
            $table->foreign('ea_client_credential_id')
                ->references('id')
                ->on('ea_client_credentials')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropForeign('offices_ea_client_credential_id_foreign');
            $table->dropIndex('offices_ea_client_credential_id_foreign');
            $table->dropColumn('ea_client_credential_id');
        });
    }

    private function getEaCredentialId(): ?int
    {
        return EAClientCredential::query()->where('name', 'default')->first()?->id;
    }
}
