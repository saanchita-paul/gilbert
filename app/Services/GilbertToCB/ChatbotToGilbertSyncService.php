<?php

namespace App\Services\GilbertToCB;

use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\ConnectionService;
use App\Models\Identification;
use App\Models\RejectionReason;
use App\Services\AddressMapperService;
use App\Services\GilbertToChatbotStatusMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ChatbotToGilbertSyncService
{

    /**
     *
     */
    public const CARD_COLOR_GREEN = 'GREEN';
    /**
     *
     */
    public const CARD_COLOR_BLUE = 'BLUE';
    /**
     *
     */
    public const CARD_COLOR_YELLOW = 'YELLOW';

    /**
     *
     */
    public const CONCESSION_CARD_TYPE_DVA = 'DVA';
    /**
     *
     */
    public const CONCESSION_CARD_TYPE_HCC = 'HCC';
    /**
     *
     */
    public const CONCESSION_CARD_TYPE_PCC = 'PCC';
    /**
     *
     */
    public const CONCESSION_CARD_TYPE_QSC = 'QSC';

    /**
     * @var
     */
    private $chatbotId;
    /**
     * @var
     */
    private $requestData;
    /**
     * @var array
     */
    private $applicationData = [];
    /**
     * @var array
     */
    private $identificationData = [];
    /**
     * @var array
     */
    private $serviceData = [];
    /**
     * @var array
     */
    private $authorizedPersonData = [];

    private $rejectionReasonData = [];

    /**
     * @param $chatbotId
     * @param $requestData
     */
    public function __construct($chatbotId, $requestData)
    {
        $this->chatbotId = $chatbotId;
        $this->requestData = $requestData;
        $this->setApplicationData();
    }

    /**
     * @return void
     */
    private function setApplicationData()
    {
        if (isset($this->requestData['personal_details'])) {
            $this->applicationData['title'] = ucfirst($this->requestData['personal_details']['title']);
            $this->applicationData['first_name'] = $this->requestData['personal_details']['first_name'];
            $this->applicationData['middle_name'] = $this->requestData['personal_details']['middle_name'];
            $this->applicationData['last_name'] = $this->requestData['personal_details']['last_name'];
            $this->applicationData['dob'] = $this->requestData['personal_details']['dob'];
        }
        if (isset($this->requestData['contact_details'])) {
            $this->applicationData['phone'] = $this->requestData['contact_details']['phone'];
            $this->applicationData['email'] = $this->requestData['contact_details']['email'];
        }
        if (isset($this->requestData['connection_details'])) {
//            $this->applicationData['is_manual_address'] = $this->requestData['connection_details']['is_manual_address'];
            $this->applicationData['billing_mannual_address'] = $this->requestData['connection_details']['billing_mannual_address'];
            $this->applicationData['country'] = $this->requestData['connection_details']['country'];
            $this->applicationData['billing_address_text'] = $this->requestData['connection_details']['billing_address_text'];
            $this->applicationData['moving_date'] = $this->requestData['connection_details']['moved_at'];
            $this->applicationData['address_text'] = $this->requestData['connection_details']['to_address'];
            $this->applicationData['is_billing_same'] = $this->requestData['connection_details']['is_billing_same'];
            $this->applicationData['address_unit'] = $this->requestData['connection_details']['flat_or_unit_number'];
            $this->applicationData['street_number'] = $this->requestData['connection_details']['street_number'];
            $this->applicationData['street_name_only'] = $this->requestData['connection_details']['street_name_only'];
            $this->applicationData['street_type'] = $this->requestData['connection_details']['street_type'];
            $this->applicationData['city'] = $this->requestData['connection_details']['suburb'];
            $this->applicationData['state'] = $this->requestData['connection_details']['state'];
            $this->applicationData['postcode'] = $this->requestData['connection_details']['to_postcode'];
            $this->applicationData['billing_unit_number'] = $this->requestData['connection_details']['billing_unit_number'];
            $this->applicationData['billing_street_name'] = $this->requestData['connection_details']['billing_street_name'];
            $this->applicationData['billing_street_type'] = $this->requestData['connection_details']['billing_street_type'];
            $this->applicationData['billing_city'] = $this->requestData['connection_details']['billing_city'];
            $this->applicationData['billing_state'] = $this->requestData['connection_details']['billing_state'];
            $this->applicationData['billing_postcode'] = $this->requestData['connection_details']['billing_postcode'];
            $this->applicationData['nmi'] = $this->requestData['connection_details']['nmi'];
            $this->applicationData['mirn'] = $this->requestData['connection_details']['mirn'];
        }
        if (isset($this->requestData['property_details'])) {
            $this->applicationData['property_type'] = $this->mapPropertType($this->requestData['property_details']['account_type']);
            $this->applicationData['tenancy_type'] = $this->mapTenancyType($this->requestData['property_details']['rent']);
            $this->applicationData['has_solar'] = $this->mapSolarType($this->requestData['property_details']['solar_panel']);
        }
        if (isset($this->requestData['identification_details'])) {
            $this->applicationData['is_email_billing'] = $this->mapEmailBillingType($this->requestData['identification_details']['billing_preference']);

            $this->identificationData = $this->mapIdentification($this->requestData['identification_details']);

        }
        if (isset($this->requestData['other_details'])) {
            $this->applicationData['is_email_billing'] = $this->mapEmailBillingType($this->requestData['other_details']['billing_preference']);
            $this->applicationData['ea_go_neutral'] = $this->requestData['other_details']['is_neutral_plan'];
            $this->applicationData['is_power_life_support'] = $this->requestData['other_details']['is_property_on_life_support'];
            $this->applicationData['additional_access_information'] = $this->requestData['other_details']['additional_access_information'];
            $this->applicationData['is_access_require'] = $this->requestData['other_details']['is_access_require'];
//            $this->applicationData['electricity_already_on'] = $this->requestData['other_details']['electricity_already_on'];
            $this->applicationData['inspection_time'] = $this->requestData['other_details']['qld_vis_inspection_time'];
//            $this->applicationData['i_am_home'] = $this->requestData['other_details']['meter_box_text'];
        }
        if (isset($this->requestData['others'])) {
//            $this->applicationData['created_by'] = $this->requestData['others']['created_by'];
//            $this->applicationData['assigned_to'] = $this->requestData['others']['assigned_to'];
//            $this->applicationData['submitted_by'] = $this->requestData['others']['submitted_by'];
            $this->applicationData['is_email_validate'] = $this->requestData['others']['is_email_validate'];
            $this->applicationData['phone_type'] = $this->requestData['others']['phone_type'];
            $this->applicationData['street_address'] = $this->requestData['others']['street_address'];
//            $this->applicationData['street_name'] = $this->requestData['others']['street_name'];
            $this->applicationData['additional_instruction'] = $this->requestData['others']['additional_instruction'];
            $this->applicationData['reason'] = $this->requestData['others']['reason'];
            $this->applicationData['has_life_support'] = $this->requestData['others']['is_property_on_life_support'];
            $this->applicationData['nmi'] = $this->requestData['others']['nmi'];
            $this->applicationData['mirn'] = $this->requestData['others']['mirn'];
            $this->applicationData['supplier'] = $this->requestData['others']['supplier'];
//            $this->applicationData['unit_number'] = $this->requestData['others']['unit_number'];
//            $this->applicationData['plan_type'] = $this->requestData['others']['plan_type'];
            $this->applicationData['status'] = $this->requestData['others']['status'];
            $this->applicationData['billing_street_number'] = $this->requestData['others']['billing_street_number'];
            $this->applicationData['billing_address_unit'] = $this->requestData['others']['billing_address_unit'];
            $this->applicationData['billing_street_address'] = $this->requestData['others']['billing_street_address'];
            $this->applicationData['has_electricity'] = $this->requestData['others']['has_electricity'];
            $this->applicationData['is_renovation_on'] = $this->requestData['others']['is_renovation_on'];
            $this->applicationData['homephone'] = $this->requestData['others']['homephone'];
            $this->applicationData['family_violance'] = $this->requestData['others']['family_violance'];
            $this->applicationData['source'] = $this->requestData['others']['source'];
            $this->applicationData['fast_connect_customer_reference'] = $this->requestData['others']['fast_connect_customer_reference'];
//            $this->applicationData['is_auto_water_submit'] = $this->requestData['others']['is_auto_water_submit'];
//            $this->applicationData['water_submit_response'] = $this->requestData['others']['water_submit_response'];
//            $this->applicationData['closing_reason'] = $this->requestData['others']['closing_reason'];
//            $this->applicationData['closed_by'] = $this->requestData['others']['closed_by'];
//            $this->applicationData['closed_at'] = $this->requestData['others']['closed_at'];
//            $this->applicationData['is_temporary_connection'] = $this->requestData['others']['is_temporary_connection'];
            $this->applicationData['connection_end_date'] = $this->requestData['others']['connection_end_date'];
//            $this->applicationData['water_next_available_date'] = $this->requestData['others']['water_next_available_date'];
//            $this->applicationData['international_phone'] = $this->requestData['others']['international_phone'];
//            $this->applicationData['after_hour_payee'] = $this->requestData['others']['after_hour_payee'];
            $this->applicationData['after_hour_flag'] = $this->requestData['others']['after_hour_flag'];
            $this->applicationData['mannual_address'] = $this->requestData['others']['mannual_address'];
//            $this->applicationData['is_address_complete'] = $this->requestData['others']['is_address_complete'];
//            $this->applicationData['billing_is_address_complete'] = $this->requestData['others']['billing_is_address_complete'];
//            $this->applicationData['state_short'] = $this->requestData['others']['state_short'];
            $this->applicationData['billing_state_short'] = $this->requestData['others']['billing_state_short'];
            $this->applicationData['billing_street_name_only'] = $this->requestData['others']['billing_street_name_only'];
            $this->applicationData['is_water_manual_submitting'] = $this->requestData['others']['is_water_manual_submitting'];
            $this->applicationData['tsa_call_status'] = $this->requestData['others']['tsa_call_status'];
            $this->applicationData['is_email_marketing'] = $this->requestData['others']['enabled_marketing_offer'];
            $this->applicationData['is_gas_life_support'] = $this->requestData['others']['has_gas_life_support'];
            $this->applicationData['is_any_unrestrained_animal'] = $this->requestData['others']['is_any_unrestrained_animal'];
            $this->applicationData['is_correspondence_email'] = $this->requestData['others']['is_correspondence_email'];
//            $this->applicationData['is_triage'] = $this->requestData['others']['is_triage'];
            $this->applicationData['is_running_submission'] = $this->requestData['others']['is_running_submission'];
            $this->applicationData['is_skip_hubspot'] = $this->requestData['others']['is_skip_hubspot'];
//            $this->applicationData['email_manually_verified_by'] = $this->requestData['others']['email_manually_verified_by'];
//            $this->applicationData['is_duplicate'] = $this->requestData['others']['is_duplicate'];
//            $this->applicationData['life_support_accepted_at'] = $this->requestData['others']['life_support_accepted_at'];
//            $this->applicationData['terms_and_conditions_accepted_at'] = $this->requestData['others']['terms_and_conditions_accepted_at'];
//            $this->applicationData['promotion_code'] = $this->requestData['others']['promotion_code'];
//            $this->applicationData['promotion_terms_and_conditions_accepted_at'] = $this->requestData['others']['promotion_terms_and_conditions_accepted_at'];
//            $this->applicationData['is_generated_caf'] = $this->requestData['others']['is_generated_caf'];
        }
        if (isset($this->requestData['concession_details'])) {
            $this->applicationData['concession_card_type'] = $this->mapConcessionCardType($this->requestData['concession_details']['concession_card_type']);
            $this->applicationData['concession_card_number'] = $this->requestData['concession_details']['concession_card_value'];
            $this->applicationData['concession_start_date'] = $this->requestData['concession_details']['concession_card_start_date'];
//            $this->applicationData['concession_end_date'] = $this->requestData['concession_details']['concession_end_date'];
        }
        if (isset($this->requestData['authorized_person'])) {
            $this->authorizedPersonData['title'] = $this->requestData['authorized_person']['title'];
            $this->authorizedPersonData['first_name'] = $this->requestData['authorized_person']['first_name'];
            $this->authorizedPersonData['middle_name'] = $this->requestData['authorized_person']['middle_name'];
            $this->authorizedPersonData['last_name'] = $this->requestData['authorized_person']['last_name'];
            $this->authorizedPersonData['email'] = $this->requestData['authorized_person']['email'];
            $this->authorizedPersonData['phone'] = $this->requestData['authorized_person']['phone'];
            $this->authorizedPersonData['dob'] = $this->requestData['authorized_person']['dob'];
            $this->authorizedPersonData['identification_type'] = $this->requestData['authorized_person']['identification_type'];
            $this->authorizedPersonData['card_number'] = $this->requestData['authorized_person']['card_number'];
            $this->authorizedPersonData['state'] = $this->requestData['authorized_person']['state'];
            $this->authorizedPersonData['country'] = $this->requestData['authorized_person']['country'];
            $this->authorizedPersonData['card_color'] = $this->requestData['authorized_person']['card_color'];
            $this->authorizedPersonData['special_number'] = $this->requestData['authorized_person']['special_number'];
            $this->authorizedPersonData['expire_date'] = $this->requestData['authorized_person']['expire_date'];
        }
        if (isset($this->requestData['connection_services'])) {
            $this->serviceData = $this->mapConnectionService($this->requestData['connection_services']);
        }
        if (isset($this->requestData['rejection_reasons'])) {
            $this->rejectionReasonData = $this->mapRejectionRejection($this->requestData['rejection_reasons']);
        }

    }


    /**
     * @return void
     */
    public function sync()
    {
        $app = ConnectionApplication::where('chatbot_id', $this->chatbotId)->firstOrFail();
        $app->update($this->applicationData);
        Identification::where('connection_application_id', $app->id)
            ->update($this->identificationData);
        ConnectionApplicationSecondaryACC::where('connection_application_id', $app->id);
    }

    /**
     *
     * @param $propertyType
     * @return int|null
     */
    private function mapPropertType($propertyType)
    {
        return match (strtolower($propertyType)) {
            'residential' => ConnectionApplication::PROPERTY_TYPE_RESIDENTIAL,
            'business' => ConnectionApplication::PROPERTY_TYPE_BUSINESS,
            default => null
        };
    }

    /**
     * @param $tenancyType
     * @return int|null
     */
    private function mapTenancyType($tenancyType)
    {
        return match ((int)$tenancyType) {
            0 => ConnectionApplication::TENANCY_TYPE_HOME_OWNER,
            1 => ConnectionApplication::TENANCY_TYPE_RENTER,
            default => null
        };
    }

    /**
     * @param $solarType
     * @return int|null
     */
    private function mapSolarType($solarType)
    {
        return match (strtolower($solarType)) {
            'solar' => ConnectionApplication::HAS_SOLAR,
            'no_solar' => ConnectionApplication::NO_SOLAR,
            default => null
        };
    }

    /**
     * @param $emailBillingType
     * @return int|null
     */
    private function mapEmailBillingType($emailBillingType)
    {
        return match (strtolower($emailBillingType)) {
            'email' => 1,
            'connection_address' => 0,
            default => null
        };
    }

    /**
     * @param $cardColorType
     * @return string|null
     */
    private function mapCardColorType($cardColorType)
    {
        return match (strtolower($cardColorType)) {
            'green' => self::CARD_COLOR_GREEN,
            'blue' => self::CARD_COLOR_BLUE,
            'yellow' => self::CARD_COLOR_YELLOW,
            default => null
        };
    }


    private function mapIdentificationState($identificationState)
    {
        return match (strtolower($identificationState)) {
            'victoria' => AddressMapperService::STATE_VIC,
            'new south wales' => AddressMapperService::STATE_NSW,
            'australian capital territory' => AddressMapperService::STATE_ACT,
            'south australia' => AddressMapperService::STATE_SA,
            'queensland' => AddressMapperService::STATE_QLD,
            'western australia' => AddressMapperService::STATE_WA,
            'tasmania' => AddressMapperService::STATE_TAS,
            'northern territory' => AddressMapperService::STATE_NT,
            default => null
        };
    }


    /**
     * @param $identityType
     * @return int|null
     */
    private function mapIDType($identityType)
    {
        return match (strtolower($identityType)) {
            'identity_passport' => Identification::TYPE_PASSPORT,
            'identity_driving_license' => Identification::TYPE_DRIVING_LICENCE,
            'identity_medicare' => Identification::TYPE_MEDICARE,
            default => null
        };
    }

    /**
     * @param $serviceType
     * @return string|null
     */
    private function mapServiceType($serviceType)
    {
        return match (strtolower($serviceType)) {
            'gas' => ConnectionService::TYPE_GAS,
            'power' => ConnectionService::TYPE_ELECTRICITY,
            'water' => ConnectionService::TYPE_WATER,
            'internet' => ConnectionService::TYPE_INTERNET,
            default => null
        };
    }

    /**
     * @param $concessionCardType
     * @return string|null
     */
    private function mapConcessionCardType($concessionCardType)
    {
        return match ($concessionCardType) {
            1 => self::CONCESSION_CARD_TYPE_DVA,
            2 => self::CONCESSION_CARD_TYPE_HCC,
            3 => self::CONCESSION_CARD_TYPE_PCC,
            4 => self::CONCESSION_CARD_TYPE_QSC,
            default => null
        };
    }

    /**
     * @param $identificationData
     * @return array
     */
    private function mapIdentification($identificationData)
    {
        $mappedIdentificationData = [];
        $identificationType = $this->mapIDType($identificationData['identification_type']);
        $mappedIdentificationData['type'] = $identificationType;
        switch ($identificationType) {
            case Identification::TYPE_PASSPORT:
                $mappedIdentificationData['card_number'] = $identificationData['passport_number'];
                $mappedIdentificationData['country'] = $identificationData['passport_country'];
                $mappedIdentificationData['expire_date'] = $identificationData['identification_expire_date'];
                break;
            case Identification::TYPE_DRIVING_LICENCE:
                $mappedIdentificationData['card_number'] = $identificationData['driving_license_number'];
                $mappedIdentificationData['state'] = $this->mapIdentificationState($identificationData['driving_license_state']);
                $mappedIdentificationData['expire_date'] = $identificationData['identification_expire_date'];

                break;
            case Identification::TYPE_MEDICARE:
                $mappedIdentificationData['card_number'] = $identificationData['medicare_card_number'];
                $mappedIdentificationData['special_number'] = $identificationData['individual_reference_number'];
                $mappedIdentificationData['card_color'] = $this->mapCardColorType($identificationData['medicare_card_color']);
                $mappedIdentificationData['expire_date'] = $identificationData['identification_expire_date'];
                break;
            default:
                break;
        }
        return $mappedIdentificationData;
    }

    private function mapServiceStatus($status)
    {
        return GilbertToChatbotStatusMapping::CB_TO_GB_MAPPING[strtolower($status)] ?? null;
    }

    /**
     * @param $serviceData
     * @return void
     */
    private function mapConnectionService($serviceData)
    {
        $app = ConnectionApplication::where('chatbot_id', $this->chatbotId)->firstOrFail();
        foreach ($serviceData as $service) {
            $serviceType = strtolower($service['service_type']) === 'electricity' ? 'power' : $service['service_type'];
            if($service['service_type']) {
                ConnectionService::query()->where('connection_application_id', $app->id)->with('reasons')
                    ->updateOrCreate(['service_type' => $serviceType], [
                        'connection_application_id' => $app->id,
                        'service_type'   => $serviceType,
                        'plan_type'   => $service['plan_type'],
                        'provider_name'   => $service['provider_name'],
                        'status'   => $this->mapServiceStatus($service['status']),
                        'connection_date'   => $service['connection_date'],
                        'submitted_at'   => $service['submitted_at'],
                        'lead_reference'   => $service['lead_reference'],
                        'quote_reference'   => $service['quote_reference'],
                        'accepted_at'   => $service['accepted_at'],
                        'rejected_at'   => $service['rejected_at'],
                        'distributor'   => $service['distributor'],
                    ]);
            }
        }
    }

    private function mapRejectionRejection($rejectionReasonData)
    {
        $app = ConnectionApplication::where('chatbot_id', $this->chatbotId)->firstOrFail();
        RejectionReason::query()->where('connection_application_id', $app->id)->delete();
        $gasServiceID = null;
        $electricityServiceId = null;
        foreach ($app->connectionServices as $service) {
            if ($service->service_type === ConnectionService::TYPE_GAS) {
                $gasServiceID = $service->id;
            }
            if ($service->service_type === ConnectionService::TYPE_ELECTRICITY) {
                $electricityServiceId = $service->id;
            }
        }
        $rejectionReasons = [];
        foreach ($rejectionReasonData as $reason) {
            if ($gasServiceID && $reason['service_type'] === 'gas') {
                $rejectionReasons[] = [
                    'connection_application_id' => $app->id,
                    'connection_service_id' => $gasServiceID,
                    'service_type' => $reason['service_type'],
                    'reason_code' => $reason['reason_code'],
                    'reason_text' => $reason['reason_text'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($electricityServiceId && $reason['service_type'] === 'electricity') {
                $rejectionReasons[] = [
                    'connection_application_id' => $app->id,
                    'connection_service_id' => $electricityServiceId,
                    'service_type' => $reason['service_type'],
                    'reason_code' => $reason['reason_code'],
                    'reason_text' => $reason['reason_text'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        RejectionReason::query()->insert($rejectionReasons);
    }


}
