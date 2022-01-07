<?php

use App\Services\FullTextSearch\FullTextQuery;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicationsFullTextSearchIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tenantName = FullTextQuery::INDEX_FT_TENANT_NAME;
        $address = FullTextQuery::INDEX_FT_ADDRESS;
        $phone = FullTextQuery::INDEX_FT_PHONE;

        DB::statement("ALTER TABLE `connection_applications` ADD FULLTEXT INDEX $tenantName (first_name, middle_name, last_name)");
        DB::statement("ALTER TABLE `connection_applications` ADD FULLTEXT INDEX $address (
                                                                        unit_number,
                                                                        street_number,
                                                                        street_name,
                                                                        city,
                                                                        postcode,
                                                                        state,
                                                                        country
                                                                    )");
        DB::statement("ALTER TABLE `connection_applications` ADD FULLTEXT INDEX $phone (phone, homephone)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->dropIndex(FullTextQuery::INDEX_FT_TENANT_NAME);
            $table->dropIndex(FullTextQuery::INDEX_FT_ADDRESS);
            $table->dropIndex(FullTextQuery::INDEX_FT_PHONE);
        });
    }
}
