<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSugerLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suger_leads', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->json("all_fields_dump");

            
            $table->dateTime("date_modified")->nullable();
            $table->boolean("deleted")->nullable();
            $table->boolean("do_not_call")->nullable();
            $table->string("phone_work")->nullable();
            $table->string("phone_other")->nullable();
            $table->string("phone_fax")->nullable();      
            
            // $table->string("alt_street_address")->nullable();
            // $table->string("alt_city")->nullable();
            // $table->string("alt_state")->nullable();
            // $table->string("alt_postcode")->nullable();
            // $table->string("atl_country")->nullable();
            
            $table->string("alt_address_street")->nullable();
            $table->string("alt_address_city")->nullable();
            $table->string("alt_address_state")->nullable();
            $table->string("alt_address_postcode")->nullable();
            $table->string("atl_address_country")->nullable();

            $table->string("assistant_phone")->nullable();
            $table->string("lead_source")->nullable();
            $table->string("lead_souce_description")->nullable();
            $table->string("status")->nullable();
            $table->string("meter_type_c")->nullable();
            $table->string("customer_type_c")->nullable();
            $table->string("current_plan_c")->nullable();
            $table->string("discounts_c")->nullable();
            $table->integer("savings_c")->nullable();
            $table->boolean("can_call_c")->nullable();

            // $table->boolean("moving_or_same_address")->nullable();
            $table->boolean("moving_c")->nullable();
            
            $table->string("business_name_sugar")->nullable();

            // $table->string("service_requirements")->nullable();
            $table->string("service_c")->nullable();


            $table->boolean("received_complaint_c")->nullable();
            $table->string("preferred_call_time_c")->nullable();
            // $table->string("Primary_address_")->nullable();
            $table->string("primary_address_level_c")->nullable();

            $table->string("external_reference_c")->nullable();
            $table->string("id_type_c")->nullable();
            $table->string("id_number_c")->nullable();
            $table->date("id_expiry_c")->nullable();
            $table->string("additional_meter_type_c")->nullable();
            $table->string("msats_tariff_code_c")->nullable();
            $table->string("foxie_agents_id_c")->nullable();
            $table->boolean("requested_bill_compare_c")->nullable();
            $table->string("water_sale_id_c")->nullable();
            $table->dateTime("no_answer_email_date_c")->nullable();
            $table->string("meter_plan_type_c")->nullable();

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
        Schema::dropIfExists('suger_leads');
    }
}
