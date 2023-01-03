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

    public static function validateEmail($email)
    {
        $url = config('gbg.base_url') . "/validate/email";
        $sourceOfTruth = 'VE_ALL';
        $locale = 'au';
        $emailTobeChecked = $email;

        try {
            $userId = config('address.gbgUserId');
            $password = config('address.gbgPassword');
            $token = base64_encode("$userId:$password");
            $authorization_header = "Basic $token";
            $response = Http::withHeaders([
                "Authorization" => $authorization_header,
            ])->get($url, [ 'address' => $emailTobeChecked, 'sourceOfTruth' => $sourceOfTruth, 'locale' => $locale ]);

            $response = json_decode($response->body(), true);

            $payloadData = $response['payload'];
            $attributes = $payloadData[0]['attributes'] ?? null;

            info('API response data', [$payloadData]);

            if (!is_null($attributes))
            {
                $result = !($attributes['email_valid'] === "INVALID"|| $attributes['email_exists'] === "INVALID");
                return $result;
            } else {
                return false;
            }

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }

    }

}
