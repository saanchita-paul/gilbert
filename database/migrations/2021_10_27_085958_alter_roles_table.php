<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = [
            'agency_assistant_property_manager',
            'agency_office_business_development_manager',
            'agency_sales_pa',
            'agency_receptionist',
        ];

        foreach ($roles as $value) {
            try {
                Role::firstOrCreate(['name' => $value]);
            } catch (\Exception $ex) {
                \Log::error($ex->getMessage());
            }
        }
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
