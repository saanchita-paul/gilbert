<?php
namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\isNull;

class HubspotContactService
{

    public function submitContact(int $id)
    {
        $application = ConnectionApplication::query()
            ->where('id', $id)
            ->first();

        $response = Http::post('https://api.hubapi.com/contacts/v1/contact?hapikey=721bd41e-2f81-4279-9ebc-ccf1822f740d', array(
            "properties" => array(
                array(
                    "property" => "hood_firstname",
                    "value" => $application->first_name
                ),
                array(
                    "property" => "hood_lastname",
                    "value" => $application->last_name
                ),
                array(
                    "property" => "hood_website",
                    "value" => $application->email
                ),
                array(
                    "property" => "hood_address",
                    "value" => $application->street_address
                ),
                array(
                    "property" => "hood_state",
                    "value" => $application->state
                )
            )
        ));
        return $response->json();
    }
}
