<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use App\Models\Agency;
use App\Models\Office;
use Illuminate\Support\Facades\DB;

class CreateExternalSourceService
{
    public function __construct()
    {
    }

    public function save($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $sourceType = $data['source_type'];
        $sourceNameDisplay = $data['source_name_display'] ?? ucwords($sourceType);
        $logo = $data['logo'] ?? null;
        $agencyName = $data['agency_name'] ?? $sourceNameDisplay . "-Hood Agency";
        $officeName = $data['office_name'] ?? $sourceNameDisplay . "-Hood Office";
        $officeEmail = $data['agency_email'] ?? $sourceType . "@hood.ai";

        $defaultOffice = $this->createDefaultOffice($agencyName, $officeName, $officeEmail);
        $externalSource = $this->createExternalSource(
            $defaultOffice,
            $email,
            $password,
            $sourceType,
            $sourceNameDisplay,
            $logo
        );
        return $externalSource;
    }

    private function createDefaultOffice($agencyName, $officeName, $email)
    {
        $agency = Agency::where('name', $agencyName)->first();
        if (!$agency) {
            $agency =  Agency::create(["name" => $agencyName, "type" => Agency::TYPE_INDEPENDENT]);
            $office = $agency->offices()->create([
                'name' => $officeName,
                'street_address' => "100 Plenty Rd",
                'city' => "Preston",
                'state' => "VIC",
                'postcode' => "3072",
                'country' => "Australia",
                'abn' => "2",
                'phone' => "0417145569",
                'email' => $email,
                'is_default_office' => true,
            ]);
        }

        return $office;
    }

    private function createExternalSource(Office $office, string $email, string $password, string $sourceType, string $sourceNameDisplay, UploadedFile $logo = null)
    {
        $newExternalSource = new ExternalSource();

        $newExternalSource = new ExternalSource();
        $newExternalSource->email = $email;
        $newExternalSource->password = Hash::make($password);
        $newExternalSource->is_active = true;
        $newExternalSource->default_office_id = $office->id;
        $newExternalSource->source_type = $sourceType;
        $newExternalSource->display_type_name = $sourceNameDisplay;
        $newExternalSource->name = $sourceNameDisplay;
        $newExternalSource->save();

        if (!empty($logo)) {
            $this->saveLogo($newExternalSource, $logo);
        }

        return $newExternalSource;
    }

    private function saveLogo(ExternalSource $source, UploadedFile $image)
    {
        $fileName = $source->source_type . "." . $image->extension();
        $path = $image->storeAs('logo', $fileName);
        $source->logo = $path;
        $source->save();

        return $source;
    }
}
