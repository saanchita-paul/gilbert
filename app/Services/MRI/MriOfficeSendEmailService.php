<?php

namespace App\Services\MRI;

use App\Models\MriOffice;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MRIOfficeNotification;

class MriOfficeSendEmailService
{
    public function sendEmail()
    {
        $mri_keys = collect((new MriApplicationKeyService())->getData());
        $keys = $mri_keys->pluck('key')->toArray();
        $data = MriOffice::query()->whereIn('key', $keys)->pluck('key')->toArray();
        $filteredData = $mri_keys->filter(fn ($item) => (!in_array(data_get($item, 'key'), $data)))->toArray();

        if (count($filteredData) > 0) {
            // Send mail notification
            Notification::route('mail', config('mri.to_mail_address'))
                ->notify(new MRIOfficeNotification($filteredData));
        }
    }
}
