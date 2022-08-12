<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConncectionAppliationAddressIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_applications', function (Blueprint $table) {
            $table->dropIndex(FullTextQuery::INDEX_FT_ADDRESS);
        });

        $addressIndex = FullTextQuery::INDEX_FT_ADDRESS;
        DB::statement("ALTER TABLE `connection_applications` ADD FULLTEXT INDEX $addressIndex (
                                                                        unit_number,
                                                                        street_number,
                                                                        street_name_only,
                                                                        city,
                                                                        postcode,
                                                                        state,
                                                                        country,
                                                                        address_text,
                                                                        street_address
                                                                    )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
