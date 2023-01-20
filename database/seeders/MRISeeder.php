<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\MriOffice;
use App\Models\Office;
use Illuminate\Database\Seeder;
use App\Services\MRI\HandleMRIOfficeService;
use App\Services\MRI\MriApplicationKeyService;

class MRISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agency =  Agency::where('name', "MRI Hood Agency")->first();
        if (!$agency) {
            $agency =  Agency::create(["name" => "MRI Hood Agency", "type" => Agency::TYPE_INDEPENDENT]);
            $office = $agency->offices()->create([
                'name' => "MRI Hood Office",
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "0417145569",
                'email' => "mri@hood.ai"
            ]);
        } else {
            $office = $agency->offices()->where('name', "MRI Hood Office")->first();
        }

        $this->deleteMriOfficeIfAny($office);
        // $this->createMriData($office);
    }

    private function createMriData(Office $office)
    {
        $mriOffice = MriOffice::where('office_id', $office->id)->first();
        if (!$mriOffice) {
            $appKeyService = new MriApplicationKeyService();
            $appKeyResponse = $appKeyService->getData();
            $appKeyResponse = array_filter($appKeyResponse, function ($v) {
                $check = str_contains(strtolower($v['company_name']), 'hood');
                return $check;
            });

            if (count($appKeyResponse) > 0) {
                $mriData = $appKeyResponse[0];
                $officeService = new HandleMRIOfficeService($office->id);
                $officeService->saveMRIOffice($mriData);
            }
        }
    }

    private function deleteMriOfficeIfAny(Office $office)
    {
        MriOffice::where('office_id', $office->id)->delete();
    }
}
