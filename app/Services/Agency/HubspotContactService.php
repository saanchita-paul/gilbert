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
                    "property" => "hood_id",
                    "value" => $application->id
                ),
                array(
                    "property" => "hood_office_id",
                    "value" => $application->office_id
                ),
                array(
                    "property" => "hood_agency_id",
                    "value" => $application->agency_id
                ),
                array(
                    "property" => "hood_created_by",
                    "value" => $application->created_by
                ),
                array(
                    "property" => "hood_assigned_to",
                    "value" => $application->assigned_to
                ),
                array(
                    "property" => "hood_title",
                    "value" => $application->title
                ),
                array(
                    "property" => "hood_first_name",
                    "value" => $application->first_name
                ),
                array(
                    "property" => "hood_last_name",
                    "value" => $application->last_name
                ),
                array(
                    "property" => "hood_email",
                    "value" => $application->email
                ),
                array(
                    "property" => "hood_phone",
                    "value" => $application->phone
                ),
                array(
                    "property" => "hood_tenancy_type",
                    "value" => $application->tenancy_type
                ),
                array(
                    "property" => "hood_dob",
                    "value" => $application->dob
                ),
                array(
                    "property" => "hood_moving_date",
                    "value" => $application->moving_date
                ),
                array(
                    "property" => "hood_address_unit",
                    "value" => $application->address_unit
                ),
                array(
                    "property" => "hood_street_address",
                    "value" => $application->street_address
                ),
                array(
                    "property" => "hood_city",
                    "value" => $application->city
                ),
                array(
                    "property" => "hood_postcode",
                    "value" => $application->postcode
                ),
                array(
                    "property" => "hood_state",
                    "value" => $application->state
                ),
                array(
                    "property" => "hood_country",
                    "value" => $application->country
                ),
                array(
                    "property" => "hood_additional_instruction",
                    "value" => $application->additional_instruction
                ),
                array(
                    "property" => "hood_address_text",
                    "value" => $application->address_text
                ),
                array(
                    "property" => "hood_reason",
                    "value" => $application->reason
                ),
                array(
                    "property" => "hood_is_email_billing",
                    "value" => $application->is_email_billing
                ),
                array(
                    "property" => "hood_property_type",
                    "value" => $application->property_type
                ),
                array(
                    "property" => "hood_has_life_support",
                    "value" => $application->has_life_support
                ),
                array(
                    "property" => "hood_has_solar",
                    "value" => $application->has_solar
                ),
                array(
                    "property" => "hood_nmi",
                    "value" => $application->nmi
                ),
                array(
                    "property" => "hood_mirn",
                    "value" => $application->mirn
                ),
                array(
                    "property" => "hood_supplier",
                    "value" => $application->supplier
                ),
                array(
                    "property" => "hood_plan_type",
                    "value" => $application->plan_type
                ),
                array(
                    "property" => "hood_status",
                    "value" => $application->status
                ),
                array(
                    "property" => "hood_created_at",
                    "value" => $application->created_at
                ),
                array(
                    "property" => "hood_updated_at",
                    "value" => $application->updated_at
                )
            )
        ));
        dd($response->body());
        return $response->json();
    }
}
