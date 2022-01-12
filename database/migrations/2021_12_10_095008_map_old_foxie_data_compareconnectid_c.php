<?php

use Foxie\Models\SugerLead;
use Foxie\Services\SugerLeadService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MapOldFoxieDataCompareconnectidC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        SugerLead::chunk(20, function($sugerLead)
        {
            foreach ($sugerLead as $lead) {
                $allDataFromDump = json_decode($lead->all_fields_dump);
                if(isset($allDataFromDump->compareconnect_id_c) && $allDataFromDump->compareconnect_id_c != ''){
                    $lead->compare_connect_id = $allDataFromDump->compareconnect_id_c;
                    $lead->save();
                }
            }
        });
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
