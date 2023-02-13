<?php

namespace App\Services\GilbertToCB;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\RejectionReason;
use App\Services\Address\AddressModel;
use App\Services\ChatBot\SendApplicationToChatbotAPI;

class GilbertToChatbotSyncService
{
    private $application;

    const CONCESSION_MAPPER = [
        'DVA' => 1,
        'HCC' => 2,
        'PCC' => 3,
        'QSC' => 4
    ];

    /** UTILITY TYPE CONSTANTS */
    const PLAN_UTILITY_TYPE_ELECTRICITY = 'electricity';
    const PLAN_UTILITY_TYPE_GAS = 'gas';
    const PLAN_UTILITY_TYPE_BOTH = 'electricity_and_gas';

    /** CONCESSION CARD TYPE */
    const CONCESSION_CARD_YES = 1;
    const CONCESSION_CARD_NO = 0;

    /** SOLAR PANEL CONSTANTS */
    const SOLAR_PANEL_YES = 'solar';
    const SOLAR_PANEL_NO = 'no_solar';
    const SOLAR_PANEL_NOT_SURE = 'solar_not_sure';

    /** PROPERTY TYPE CONSTANTS */
    const PROPERTY_TYPE_RESIDENTIAL = 'residential';
    const PROPERTY_TYPE_BUSINESS = 'business';

    const INSPECTION_TIME_MAPPER = [
        '8:00am - 12:00pm' => '8AM - 12PM',
        '1:00pm - 5:00pm' => '1PM - 5PM',
        '8:00am - 1:00pm' => '8AM - 1PM',
        '9:00am - 2:00pm' => '9AM - 2PM',
        '10:00am - 3:00pm' => '10AM - 3PM',
        '11:00am - 4:00pm' => '11AM - 4PM',
        '12:00pm - 5:00pm' => '12PM - 5PM',
        '1:00pm - 6:00pm' => '1PM - 6PM',
    ];


    public function __construct($applicationId)
    {
        $this->application = ConnectionApplication::query()->where('id', $applicationId)->with([
            'identification',
            'connectionServices',
            'authorizedPerson',
            'office',
            'agency',
            'powershopPaymentInfo'
        ])->firstOrFail();
    }

    /**
     * sync
     *
     */
    public function sync()
    {
        $this->application->update(['is_locked' => true]);
        return (new SendApplicationToChatbotAPI())->postApi($this->getMappedData());
    }

    public function lockApp()
    {
        return $this->application->update(['is_locked' => true]);
    }

    /**
     * map data
     *
     * @return array
     */
    private function getMappedData(): array
    {
        return [
            "connection_application_id" => $this->application->id,
            "office_name" => $this->application->office->name,
            "agency_name" => $this->application->agency->name,
            "title" => strtolower($this->application->title),
            "first_name" => $this->application->first_name,
            "middle_name" => $this->application->middle_name,
            "last_name" => $this->application->last_name,
            "full_name" => $this->mapFullName($this->application->first_name, $this->application->middle_name, $this->application->last_name),
            "email" => $this->application->email,
            "is_email_validate" => $this->application->is_email_validate,
            "phone" => $this->application->phone,
            "phone_type" => $this->application->phone_type,
            "rent" => $this->mapTenancyType($this->application->tenancy_type),
            "dob" => $this->application->dob,
            "which_utility" => $this->mapServiceType($this->application->connectionServices),
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
            "billing_preference" => $this->mapBillingType($this->application->is_email_billing), // is_email_billing also exists in chatbot moving utility table
            "account_type" => $this->mapPropertyType($this->application->property_type),
            "is_property_on_life_support" => $this->application->has_life_support,
            "solar_panel" => $this->mapSolarPanel($this->application->has_solar),
            "nmi" => $this->application->nmi,
            "mirn" => $this->application->mirn,
            "supplier" => $this->application->supplier,
            "unit_number" => $this->application->unit_number,
            "status" => $this->application->status,
            "hubspot_contact_id" => $this->application->hubspot_contact_id,
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
            "homephone" => $this->application->homephone,
            "qld_vis_inspection_time" => $this->mapInspectionTime($this->application->inspection_time),
            "family_violance" => $this->application->family_violance,
            "source" => $this->application->source,
            "is_contacted" => $this->application->is_contacted,
            "fast_connect_customer_reference" => $this->application->fast_connect_customer_reference,
            "connection_end_date" => $this->application->connection_end_date,
            "after_hour_payee" => $this->application->after_hour_payee,
            "after_hour_flag" => $this->application->after_hour_flag,
            "street_type" => $this->application->street_type,
            "billing_street_type" => $this->application->billing_street_type,
            "mannual_address" => $this->application->mannual_address,
            "billing_mannual_address" => $this->application->billing_mannual_address,
            "billing_state_short" => $this->application->billing_state_short,
            "street_name_only" => $this->application->street_name_only,
            "billing_street_name_only" => $this->application->billing_street_name_only,
            "is_neutral_plan" => $this->application->ea_go_neutral,
            "enabled_marketing_offer" => $this->application->is_email_marketing,
            "is_access_require" => $this->application->is_access_require,
            "has_gas_life_support" => $this->application->is_gas_life_support,
            "is_any_unrestrained_animal" => $this->application->is_any_unrestrained_animal,
            "has_concession_card" => $this->mapHasConcessionCard($this->application->concession_card_type),
            "concession_card_type" => $this->mapConcessionCardType($this->application->concession_card_type),
            "concession_card_value" => $this->application->concession_card_number,
            "concession_card_start_date" => $this->application->concession_start_date,
            "additional_access_information" => $this->application->additional_access_information,
            "is_correspondence_email" => $this->application->is_correspondence_email,
            "hood_utm_source" => $this->application->hood_utm_source,
            "hood_utm_content" => $this->application->hood_utm_content,
            "hood_utm_medium" => $this->application->hood_utm_medium,
            "hood_hss_channel" => $this->application->hood_hss_channel,
            "is_running_submission" => $this->application->is_running_submission,
            "is_skip_hubspot" => $this->application->is_skip_hubspot,
            "is_locked" => !$this->application->is_locked,
            "is_closed" => $this->mapIsClosed($this->application->status),
            "is_escalated" => $this->mapIsEscalated($this->application->status),
            "connection_services" => $this->application->connectionServices ? $this->application->connectionServices->toArray() : [],
            "identification" => $this->application->identification ? $this->application->identification->toArray() : null,
            "authorized_person" => $this->application->authorizedPerson ? $this->application->authorizedPerson->toArray() : null,
            "rejection_reasons" => $this->mapRejectionReasons(),
            "payment_sync_data" => $this->mapPaymentData(),
        ];
    }


    /**
     * map concession card type
     *
     * @param $data
     * @return int|null
     */
    public function mapConcessionCardType($data): ?int
    {
        return self::CONCESSION_MAPPER[strtoupper($data)] ?? null;
    }

    /**
     * map tenancy type
     *
     * @param $tenancyType
     * @return int|null
     */
    private function mapTenancyType($tenancyType): ?int
    {
        return match ((int)$tenancyType) {
            1 => 1,
            2 => 0,
            default => null
        };
    }

    /**
     * map billing type
     *
     * @param $billingType
     * @return string|null
     */
    private function mapBillingType($billingType): ?string
    {
        return match ((int)$billingType) {
            1 => 'email',
            0 => 'connection_address',
            default => null
        };
    }

    /**
     * map full name
     *
     * @param $firstName
     * @param $middleName
     * @param $lastName
     * @return string|null
     */
    private function mapFullName($firstName, $middleName, $lastName): ?string
    {
        return !empty($middleName) ? $firstName . ' ' . $middleName . ' ' . $lastName : $firstName . ' ' . $lastName;
    }

    /**
     * @param $services
     * @return string
     */
    private function mapServiceType($services): string
    {
        $filterServices = [];
        foreach ($services as $service) {
            if ($service['service_type'] === ConnectionService::TYPE_GAS || $service['service_type'] === ConnectionService::TYPE_ELECTRICITY) {
                $filterServices[] = $service;
            }
        }
        if (count($filterServices) == 2) {
            return self::PLAN_UTILITY_TYPE_BOTH;
        }
        if (count($filterServices) == 1 && $filterServices[0]['service_type'] == ConnectionService::TYPE_GAS) {
            return self::PLAN_UTILITY_TYPE_GAS;
        }
        return self::PLAN_UTILITY_TYPE_ELECTRICITY;
    }

    /**
     * @param $concessionCardType
     * @return int
     */
    private function mapHasConcessionCard($concessionCardType): int
    {
        return $concessionCardType ? self::CONCESSION_CARD_YES : self::CONCESSION_CARD_NO;
    }

    /**
     * map is closed
     *
     * @param $status
     * @return int
     */
    private function mapIsClosed($status): int
    {
        return $status == 8 ? 1 : 0;
    }

    /**
     * map is escalated
     *
     * @param $status
     * @return int
     */
    private function mapIsEscalated($status): int
    {
        return $status == 3 ? 1 : 0;
    }

    /**
     * map solar panel type
     *
     * @param $solar
     * @return string|null
     */
    private function mapSolarPanel($solar): ?string
    {
        return match ((int)$solar) {
            1 => self::SOLAR_PANEL_YES,
            2 => self::SOLAR_PANEL_NO,
            3 => self::SOLAR_PANEL_NOT_SURE,
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
            1 => self::PROPERTY_TYPE_RESIDENTIAL,
            2 => self::PROPERTY_TYPE_BUSINESS,
            default => null
        };
    }

    /**
     * map inspection time
     *
     * @param $inspectionTime
     * @return string|null
     */
    private function mapInspectionTime($inspectionTime): ?string
    {
        return self::INSPECTION_TIME_MAPPER[strtolower($inspectionTime)] ?? null;
    }

    /**
     * map rejection reasons
     *
     * @return array
     */
    private function mapRejectionReasons(): array
    {
        $servicesId = $this->application->connectionServices
            ? $this->application->connectionServices->pluck('id')->toArray()
            : [];

        return !empty($servicesId)
            ? RejectionReason::query()->whereIn('connection_service_id', $servicesId)->get()->toArray()
            : [];

    }

    public function mapPaymentData(): array
    {
        return $this->application->powershopPaymentInfo ? [
            'estimated_elec_billing_cost' => $this->application->powershopPaymentInfo->estimated_elec_billing_cost,
            'estimated_gas_billing_cost' => $this->application->powershopPaymentInfo->estimated_gas_billing_cost,
        ] : [];
    }
}

