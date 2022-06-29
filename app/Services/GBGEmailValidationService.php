<?php

namespace App\Services;

use App\Models\APILog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GBGEmailValidationService
{

    /**
     * validate email
     *
     * @throws \Exception
     */

    public function validateEmail($email)
    {
        $url = config('gbg.base_url') . "/validate/email";
        $sourceOfTruth = 'VE_ALL';
        $locale = 'au';
        $emailTobeChecked = $email;

        try {
            $authorization_header = "Basic aG9vZG1vdmV0ZWNoX3Rlc3RfdXNlcjpmM3NlN04xNEdyQ3hIV1FEZ0FKVHU3d2x1Rnc3akRXOQ==";
            $response = Http::withHeaders([
                "Authorization" => $authorization_header,
            ])
            ->get($url, [ 'address' => $emailTobeChecked, 'sourceOfTruth' => $sourceOfTruth, 'locale' => $locale ]);

            $response = json_decode($response->body(), true);

            $payloadData = $response['payload'];
            $attributes = $payloadData[0]['attributes'];

            info('hi ', $response);
            info('hi 2', $payloadData);
            info('h', [$attributes]);
            info('hi 3', [$attributes['mailserver_exists']]);
            info('hi 4', [$attributes['email_exists']]);

            if (!is_null($attributes))
            {
                $result = !($attributes['email_valid'] === "INVALID" || $attributes['email_exists'] === "INVALID");
            }

            return $result;

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }

    }

}
