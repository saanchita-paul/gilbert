<?php

namespace App\Services\Agency;

use App\Models\APILog;
use App\Models\ConnectionApplication;
use App\Models\Identification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubspotContactService
{
    private array|Collection|ConnectionApplication|Model $application;

    public function __construct(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices']);
    }

    /**
     * creating new contact
     *
     * @throws \Exception
     */
    public function create()
    {
        $url = config('hub_spot.create_contact') . config('hub_spot.api_key');
        $url = APILog::setLoggerQuery($url, APILog::API_HB_CREATE_CONTACT);

        $response = Http::post($url, [
            "properties" => $this->getProperties()
        ]);
        $body = json_decode($response->body(), true);

        if (empty($body['vid'])) {
            Log::error($response->body());
            throw new \Exception("[HubspotContactService] failed to create contact");
        }

        $this->application->update(['hubspot_contact_id' => $body['vid']]);
    }

    /**
     * updating contact
     *
     * @throws \Exception
     */
    public function update()
    {
        $vid = $this->application->hubspot_contact_id;

        $url = str_replace('${id}', $vid, config('hub_spot.update_contact')) . config('hub_spot.api_key');
        $url = APILog::setLoggerQuery($url, APILog::API_HB_UPDATE_CONTACT);

        $response = Http::post($url, [
            "properties" => $this->getProperties()
        ]);

        if (!$response->successful()) {
            Log::info('[HubspotContactService]: response body');
            Log::info($response->body());
            throw new \Exception('[HubspotContactService] Contact update failed, check log.');
        }
    }

    private function getProperties(): array
    {
        return [
            [
                "property" => "hood_id",
                "value" => $this->application->id
            ],
            [
                "property" => "phone",
                "value" => $this->application->phone
            ],
            [
                "property" => "lifecyclestage",
                "value" => 'salesqualifiedlead'
            ],
            [
                "property" => "hood_office_id",
                "value" => $this->application->office_id
            ],
            [
                "property" => "hood_agency_id",
                "value" => $this->application->agency_id
            ],
            [
                "property" => "hood_created_by",
                "value" => $this->application->created_by
            ],
            [
                "property" => "hood_assigned_to",
                "value" => $this->application->assigned_to
            ],
            [
                "property" => "hood_title",
                "value" => $this->application->title
            ],
            [
                "property" => "firstname",
                "value" => $this->application->first_name
            ],
            [
                "property" => "lastname",
                "value" => $this->application->last_name
            ],
            [
                "property" => "email",
                "value" => $this->application->email
            ],
            [
                "property" => "hood_phone",
                "value" => $this->application->phone
            ],
            [
                "property" => "hood_tenancy_type",
                "value" => $this->application->tenancy_type
            ],
            [
                "property" => "hood_dob",
                "value" => $this->application->dob
            ],
            [
                "property" => "hood_moving_date",
                "value" => $this->application->moving_date
            ],
            [
                "property" => "hood_address_unit",
                "value" => $this->application->address_unit
            ],
            [
                "property" => "hood_street_address",
                "value" => $this->application->street_address
            ],
            [
                "property" => "hood_city",
                "value" => $this->application->city
            ],
            [
                "property" => "hood_postcode",
                "value" => $this->application->postcode
            ],
            [
                "property" => "hood_state",
                "value" => $this->application->state
            ],
            [
                "property" => "hood_country",
                "value" => $this->application->country
            ],
            [
                "property" => "hood_additional_instruction",
                "value" => $this->application->additional_instruction
            ],
            [
                "property" => "hood_address_text",
                "value" => $this->application->address_text
            ],
            [
                "property" => "hood_reason",
                "value" => $this->application->reason
            ],
            [
                "property" => "hood_is_email_billing",
                "value" => $this->application->is_email_billing
            ],
            [
                "property" => "hood_property_type",
                "value" => $this->application->property_type
            ],
            [
                "property" => "hood_has_life_support",
                "value" => $this->application->has_life_support
            ],
            [
                "property" => "hood_has_solar",
                "value" => $this->application->has_solar
            ],
            [
                "property" => "hood_nmi",
                "value" => $this->application->nmi
            ],
            [
                "property" => "hood_mirn",
                "value" => $this->application->mirn
            ],
            [
                "property" => "hood_supplier",
                "value" => $this->application->supplier
            ],
            [
                "property" => "hood_plan_type",
                "value" => $this->application->plan_type
            ],
            [
                "property" => "hood_status",
                "value" => $this->application->status
            ],
            [
                "property" => "hood_created_at",
                "value" => $this->application->created_at
            ],
            [
                "property" => "hood_updated_at",
                "value" => $this->application->updated_at
            ],
            [
                "property" => "hood_unit_number",
                "value" => $this->application->unit_number
            ],
            [
                "property" => "hood_street_number",
                "value" => $this->application->street_number
            ],
            [
                "property" => "hood_ea_sales_id",
                "value" => $this->application->ea_sales_id
            ],
            [
                "property" => "hood_identity_type",
                "value" => $this->getIdentityType($this->application->identification?->type),
            ],
            [
                "property" => "hood_medicare_expire_date",
                "value" => $this->formatDate(Identification::TYPE_MEDICARE, $this->application->identification?->expire_date),
            ],
            [
                "property" => "hood_medicare_special_number",
                "value" => $this->application->identification?->special_number ?? '',
            ],
            [
                "property" => "hood_medicare_card_color",
                "value" => $this->application->identification?->card_color ?? 'null',
            ],
            [
                "property" => "hood_medicare_number",
                "value" => $this->checkIDType(Identification::TYPE_MEDICARE, $this->application->identification?->card_number) ?? '',
            ],
            [
                "property" => "hood_driving_licence_expire_date",
                "value" => $this->formatDate(Identification::TYPE_DRIVING_LICENCE, $this->application->identification?->expire_date),
            ],
            [
                "property" => "hood_driving_licence_state",
                "value" => $this->checkIDType(Identification::TYPE_DRIVING_LICENCE, $this->application->identification?->state) ?? '',
            ],
            [
                "property" => "hood_driving_licence_number",
                "value" => $this->checkIDType(Identification::TYPE_DRIVING_LICENCE, $this->application->identification?->card_number) ?? '',
            ],
            [
                "property" => "hood_passport_expire_date",
                "value" => $this->formatDate(Identification::TYPE_PASSPORT, $this->application->identification?->expire_date),
            ],
            [
                "property" => "hood_passport_number",
                "value" => $this->checkIDType(Identification::TYPE_PASSPORT, $this->application->identification?->card_number) ?? '',
            ],
            [
                "property" => "hood_passport_country",
                "value" => $this->application->identification?->country ?? '',
            ],
            [
                "property" => "hood_services",
                "value" => join(',', $this->application->connectionServices->pluck('service_type')->toArray()),
            ],
            [
                "property" => "hs_lead_status",
                "value" => $this->getStatus(),
            ],
            [
                "property" => "hood_business",
                "value" => $this->getHoodBusiness(),
            ],
            [
                "property" => "hood_lead_source",
                "value" => $this->getLeadSource(),
            ],
            [
                "property" => "hood_real_estate_agency",
                "value" => $this->application->getAgencyName(),
            ],

        ];
    }

    /**
     * @param $type
     * @param string|null $date
     * @return string
     */
    private function formatDate($type, ?string $date): ?string
    {
        $value = $this->checkIDType($type, $date);
        try {
            return $value ? (new Carbon($value)) : null;
        } catch (\Exception $exception) {
            Log::error("[HubspotContactService] Failed parsing expire date for type: $type, value: $value");
            Log::error($exception->getTraceAsString());
            return null;
        }
    }

    /**
     * @param int|null $type
     * @return string
     */
    private function getIdentityType(?int $type): string
    {
        return $type ? Identification::MAP_TYPE[$type] : '';
    }

    /**
     * @param $type
     * @param $value
     * @return mixed|null
     */
    private function checkIDType($type, $value)
    {
        return $this->application->identification?->type === $type ? $value : null;
    }

    /**
     * @return string
     */
    private function getStatus(): string
    {
        return match ($this->application->status) {
            ConnectionApplication::STATUS_UNASSIGNED => 'NEW',
            default => 'IN_PROGRESS' //todo: handle default correctly
        };
    }

    private function getHoodBusiness()
    {
        $source = $this->application->source;

        return match ($source) {
            ConnectionApplication::SOURCE_FOXIE => 'Foxie',
            ConnectionApplication::SOURCE_HOOD => 'HOOD',
            ConnectionApplication::SOURCE_IGNITE => 'Ignite',
            4 => 'OurProperty',
            5 => 'PropertyMe',
            default => 'HOOD'
        };

    }

    private function getLeadSource()
    {
        $agencyName = $this->application?->agency?->id;

        return match ($agencyName) {
           17 => 'HOOD',
            default => 'REA'
        };
    }

}
