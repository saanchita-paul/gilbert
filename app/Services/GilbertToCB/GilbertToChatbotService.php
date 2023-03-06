<?php

namespace App\Services\GilbertToCB;

use App\Models\ConnectionApplication;
use App\Services\Address\AddressModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class GilbertToChatbotService
{

    private array|Collection|ConnectionApplication|Model $application;
    const CONCESSION_MAPPER = [
        'DVA' => 1,
        'HCC' => 2,
        'PCC' => 3,
        'QSC' => 4
    ];

    /** PROPERTY TYPE CONSTANTS */
    const PROPERTY_TYPE_RESIDENTIAL = 'residential';
    const PROPERTY_TYPE_BUSINESS = 'business';


    public function __construct($id)
    {
        $this->application = ConnectionApplication::query()->where('id', $id)->with([
            'identification',
            'connectionServices',
            'authorizedPerson',
            'office',
            'agency'
        ])->firstOrFail();

        // Set property type
        $this->setPropertyType();
    }


    /**
     * @throws \Exception
     */
    public function create()
    {
        $url = config('bot.root_url') . '/api/gilbert-application';
        $response = Http::post($url, $this->getProperties());
        if ($response->status() === 201) {
            $this->application->update(['chatbot_id' => $response->json()['moving_utility_id'], 'is_locked' => true]);
        } else {
            \Log::error(json_encode($response->body()));
            throw new \Exception('Send to Chatbot is not successful');
        }
    }


    private function getProperties(): array
    {
        return [
            "connection_application_id" => $this->application->id,
            "office_name" => $this->application->office->name,
            "agency_name" => $this->application->agency->name,
            "moving_utility_id" => $this->application->moving_utility_id,
            "title" => strtolower($this->application->title),
            "first_name" => $this->application->first_name,
            "middle_name" => $this->application->middle_name,
            "last_name" => $this->application->last_name,
            "email" => $this->application->email,
            "is_email_validate" => $this->application->is_email_validate,
            "phone" => $this->application->phone,
            "phone_type" => $this->application->phone_type,
            "rent" => $this->mapTenancyType($this->application->tenancy_type),
            "dob" => $this->application->dob,
            "moved_at" => $this->application->moving_date,
            "flat_or_unit_number" => $this->application->address_unit,
            "street_address" => $this->application->street_address,
            "street_number" => $this->application->street_number,
            "street_name" => $this->application->street_name,
            "suburb" => $this->application->city,
            "to_postcode" => $this->application->postcode,
            "state" => AddressModel::mapStateToShort($this->application->state),
            "country" => $this->application->country,
            "additional_instruction" => $this->application->additional_instruction,
            "to_address" => $this->application->address_text,
            "reason" => $this->application->reason,
            "billing_preference" => $this->mapbillingType($this->application->is_email_billing),
            "account_type" => $this->mapPropertyType($this->application->property_type),
            "is_property_on_life_support" => $this->application->has_life_support,
            "solar_panel" => $this->application->has_solar,
            "nmi" => $this->application->nmi,
            "mirn" => $this->application->mirn,
            "supplier" => $this->application->supplier,
            "unit_number" => $this->application->unit_number,
            "plan_type" => $this->application->plan_type,
            "status" => $this->application->status,
            "hubspot_contact_id" => $this->application->hubspot_contact_id,
            "ea_sales_id" => $this->application->ea_sales_id,
            "billing_unit_number" => $this->application->billing_unit_number,
            "billing_street_number" => $this->application->billing_street_number,
            "billing_street_name" => $this->application->billing_street_name,
            "billing_address_text" => $this->application->billing_address_text,
            "billing_address_unit" => $this->application->billing_address_unit,
            "billing_street_address" => $this->application->billing_street_address,
            "billing_city" => $this->application->billing_city,
            "billing_state" => $this->application->billing_state,
            "billing_postcode" => $this->application->billing_postcode,
            "is_billing_same" => $this->application->is_billing_same,
            "has_electricity" => $this->application->has_electricity,
            "is_renovation_on" => $this->application->is_renovation_on,
            "vendor_id" => $this->application->vendor_id,
            "homephone" => $this->application->homephone,
            "qld_vis_inspection_time" => $this->application->inspection_time,
            "family_violance" => $this->application->family_violance,
            "source" => $this->application->source,
            "is_contacted" => $this->application->is_contacted,
            "fast_connect_customer_reference" => $this->application->fast_connect_customer_reference,
            "is_auto_water_submit" => $this->application->is_auto_water_submit,
            "water_submit_response" => $this->application->water_submit_response,
            "app_close_reason_id" => $this->application->app_close_reason_id,
            "closing_reason" => $this->application->closing_reason,
            "closed_by" => $this->application->closed_by,
            "closed_at" => $this->application->closed_at,
            "is_temporary_connection" => $this->application->is_temporary_connection,
            "connection_end_date" => $this->application->connection_end_date,
            "water_next_available_date" => $this->application->water_next_available_date,
            "international_phone" => $this->application->international_phone,
            "after_hour_payee" => $this->application->after_hour_payee,
            "after_hour_flag" => $this->application->after_hour_flag,
            "street_type" => $this->application->street_type,
            "billing_street_type" => $this->application->billing_street_type,
            "mannual_address" => $this->application->mannual_address,
            "billing_mannual_address" => $this->application->billing_mannual_address,
            "is_address_complete" => $this->application->is_address_complete,
            "billing_is_address_complete" => $this->application->billing_is_address_complete,
            "state_short" => $this->application->state_short,
            "billing_state_short" => $this->application->billing_state_short,
            "street_name_only" => $this->application->street_name_only,
            "billing_street_name_only" => $this->application->billing_street_name_only,
            "is_water_manual_submitting" => $this->application->is_water_manual_submitting,
            "tsa_id" => $this->application->tsa_id,
            "tsa_call_status" => $this->application->tsa_call_status,
            "tsa_lead_id" => $this->application->tsa_lead_id,
            "is_neutral_plan" => $this->application->ea_go_neutral,
            "enabled_marketing_offer" => $this->application->is_email_marketing,
            "is_access_require" => $this->application->is_access_require,
            "has_gas_life_support" => $this->application->is_gas_life_support,
            "is_any_unrestrained_animal" => $this->application->is_any_unrestrained_animal,
            "concession_card_type" => $this->mapConcessionCardType($this->application->concession_card_type),
            "concession_card_value" => $this->application->concession_card_number,
            "concession_card_start_date" => $this->application->concession_start_date,
            "concession_end_date" => $this->application->concession_end_date,
            "additional_access_information" => $this->application->additional_access_information,
            "is_power_life_support" => $this->application->is_power_life_support,
            "is_correspondence_email" => $this->application->is_correspondence_email,
            "hood_utm_source" => $this->application->hood_utm_source,
            "hood_utm_content" => $this->application->hood_utm_content,
            "hood_utm_medium" => $this->application->hood_utm_medium,
            "hood_hss_channel" => $this->application->hood_hss_channel,
            "sumo_uuid" => $this->application->sumo_uuid,
            "is_triage" => $this->application->is_triage,
            "is_running_submission" => $this->application->is_running_submission,
            "is_skip_hubspot" => $this->application->is_skip_hubspot,
            "email_manually_verified_by" => $this->application->email_manually_verified_by,
            "is_duplicate" => $this->application->is_duplicate,
            "duplication_group_id" => $this->application->duplication_group_id,
            "life_support_accepted_at" => $this->application->life_support_accepted_at,
            "terms_and_conditions_accepted_at" => $this->application->terms_and_conditions_accepted_at,
            "eligible_for_concessions" => $this->application->eligible_for_concessions,
            "promotion_code" => $this->application->promotion_code,
            "promotion_terms_and_conditions_accepted_at" => $this->application->promotion_terms_and_conditions_accepted_at,
            "is_generated_caf" => $this->application->is_generated_caf,
            "connection_services" => $this->application->connectionServices ? $this->application->connectionServices->toArray() : [],
            "identification" => $this->application->identification ? $this->application->identification->toArray() : null,
            "authorized_person" => $this->application->authorizedPerson ? $this->application->authorizedPerson->toArray() : null
        ];
    }

    public function mapConcessionCardType($data)
    {
        return self::CONCESSION_MAPPER[strtoupper($data)] ?? null;
    }

    private function mapTenancyType($tenancyType)
    {
        return match ((int)$tenancyType) {
            1 => 1,
            2 => 0,
            default => null
        };
    }

    private function mapbillingType($billingType)
    {
        return match ((int)$billingType) {
            1 => 'email',
            0 => 'connection_address',
            default => null
        };
    }

    /**
     * map property type
     *
     * @param $propertyType
     * @return string|null
     */
    private function mapPropertyType($propertyType): ?string
    {
        return match ((int)$propertyType) {
            2 => self::PROPERTY_TYPE_BUSINESS,
            default => self::PROPERTY_TYPE_RESIDENTIAL
        };
    }


    /**
     * Set property type
     *
     * @return void
     */
    public function setPropertyType(): void
    {
        if (!$this->application->property_type) {
            $this->application->property_type = ConnectionApplication::PROPERTY_TYPE_RESIDENTIAL;
            $this->application->save();
        }
    }

}

