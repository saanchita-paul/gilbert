<?php

namespace App\Services\MRI;

use App\Models\MriOffice;
use App\Jobs\MriOfficeJob;

class MriOfficeSendEmailService
{
    public function sendEmail()
    {
        $mri_keys = (new MriApplicationKeyService())->getData();
        $mri_keys = collect($mri_keys);
        $keys = $mri_keys->pluck('key')->toArray();

        $exists = MriOffice::query()->whereIn('key', $keys)->pluck('key')->toArray();

        $filteredData = $mri_keys->filter(fn($item) => (
            !in_array(data_get($item, 'key'), $exists)
        ))->toArray();

        if (count($filteredData) > 0) {
            // send mail
            dispatch(new MriOfficeJob($filteredData));
            return 'Email Sent';
        }
        return 'Email not sent';
    }
}
