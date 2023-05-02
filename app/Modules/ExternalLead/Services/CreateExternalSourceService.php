<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use App\Models\Agency;
use App\Models\Office;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class CreateExternalSourceService
{
    private Agency $agency;
    private ExternalSource $source;

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

        try {
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
        } catch (\Exception $e) {
            if (!empty($this->agency->id)) {
                $this->agency->delete();
            }
            if (!empty($this->source->id)) {
                $this->source->delete();
            }
            throw $e;
        }
    }

    private function createDefaultOffice($agencyName, $officeName, $email)
    {
        $this->agency =  Agency::create(["name" => $agencyName, "type" => Agency::TYPE_INDEPENDENT]);
        $office = $this->agency->offices()->create([
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

        return $office;
    }

    /**
     * @throws FileNotFoundException
     */
    private function createExternalSource(Office $office, string $email, string $password, string $sourceType, string $sourceNameDisplay, UploadedFile $logo = null)
    {
        $this->source = new ExternalSource();
        $this->source->email = $email;
        $this->source->password = Hash::make($password);
        $this->source->is_active = true;
        $this->source->default_office_id = $office->id;
        $this->source->source_type = $sourceType;
        $this->source->name = $sourceNameDisplay;
        $this->source->source_id = (ExternalSource::orderBy('source_id', 'desc')->first()->source_id ?? 0) + 1;
        $this->source->order = (ExternalSource::orderBy('order', 'desc')->first()->order ?? 0) + 1;
        $this->source->save();

        if (!empty($logo)) {
            $this->saveLogo($this->source, $logo);
        } else {
            $this->source->logo = 'hood.png';
            $this->source->save();
        }

        return $this->source;
    }

    /**
     * @throws FileNotFoundException
     */
    private function saveLogo(ExternalSource $source, UploadedFile $image)
    {
        $fileName = $source->source_type . '.' . $image->extension();
        Storage::disk('public')->put('images/company/' . $fileName, File::get($image));
        $source->logo = $fileName;
        $source->save();
        return $source;
    }
}
