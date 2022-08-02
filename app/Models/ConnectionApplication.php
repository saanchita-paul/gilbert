<?php

namespace App\Models;

use ExternalLead\Models\TApp;
use Foxie\Models\SugerLead;
use Ignite\Models\IgniteLead;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OurProperty\Models\OurProperty;
use phpDocumentor\Reflection\Utils;
use PropertyMe\PropertyMeLead;

/**
 * App\Models\ConnectionApplication
 *
 * @property int $id
 * @property int $office_id
 * @property int $agency_id
 * @property int $created_by
 * @property int|null $assigned_to
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $homephone
 * @property int|null $tenancy_type
 * @property int|null $phone_type
 * @property string|null $dob
 * @property string|null $moving_date
 * @property string|null $address_unit
 * @property string|null $street_address
 * @property string|null $street_name
 * @property string|null $billing_street_name
 * @property string|null $billing_street_number
 * @property string|null $address_text
 * @property string|null $unit_number
 * @property string|null $street_number
 * @property string|null $city
 * @property string|null $postcode
 * @property string|null $state
 * @property string|null $inspection_time
 * @property string|null $country
 * @property string|null $billing_address_unit
 * @property string|null $billing_street_address
 * @property string|null $billing_city
 * @property string|null $billing_postcode
 * @property string|null $billing_state
 * @property string|null $billing_country
 * @property string|null $additional_instruction
 * @property string|null $billing_address_text
 * @property string|null $reason
 * @property int|null $is_email_billing
 * @property int|null $has_electricity
 * @property int|null $is_renovation_on
 * @property int|null $property_type
 * @property int|null $has_life_support
 * @property int|null $has_solar
 * @property string|null $nmi
 * @property string|null $mirn
 * @property string|null $family_violance
 * @property int|null $supplier
 * @property int|null $plan_type
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency $agency
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ApplicationNote[] $applicationNotes
 * @property-read int|null $application_notes_count
 * @property-read \App\Models\AgentProfile|null $assignedTo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConnectionService[] $connectionServices
 * @property-read int|null $connection_services_count
 * @property-read \App\Models\AgentProfile $createdBy
 * @property-read \App\Models\Identification|null $identification
 * @property-read \App\Models\Office $office
 * @method static \Database\Factories\ConnectionApplicationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereAdditionalInstruction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereAddressText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereAddressUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereHasLifeSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereHasSolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereIsEmailBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereMirn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereMovingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereNmi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication wherePlanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereTenancyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionApplication whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConnectionApplication extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'office_id',
        'agency_id',
        'created_by',
        'assigned_to',
        'first_name',
        'middle_name',
        'family_violance',
        'has_electricity',
        'inspection_time',
        'last_name',
        'email',
        'phone',
        'international_phone',
        'homephone',
        'phone_type',
        'tenancy_type',
        'dob',
        'moving_date',
        'address_unit',
        'street_address',
        'city',
        'postcode',
        'state',
        'state_short',
        'country',
        'additional_instruction',
        'address_text',
        'is_email_billing',
        'property_type',
        'has_life_support',
        'has_solar',
        'is_renovation_on',
        'nmi',
        'mirn',
        'is_escalated',
        'supplier',
//        'plan_type',
        'status',
        'ea_sales_id',
        'unit_number',
        'street_number',
        'street_name',
        'street_name_only',
        'hubspot_contact_id',
        'billing_unit_number',
        'billing_street_number',
        'billing_street_name',
        'billing_address_text',
        'billing_address_unit',
        'billing_street_address',
        'billing_city',
        'billing_postcode',
        'is_billing_same',
        'submitted_by',
        'vendor_id',
        'is_contacted',
        'is_auto_water_submit',
        'water_submit_response',
        'source',
        'connection_end_date',
        'is_temporary_connection',
        'water_next_available_date',
        'after_hour_payee',
        'after_hour_flag',
        'tsa_call_status',
        'tsa_lead_id',
        'unit_number',
        'street_type',
        'billing_state',
        'billing_street_type',
        'mannual_address',
        'billing_mannual_address',
        'billing_state_short',
        'billing_street_name_only',
        'is_address_complete',
        'billing_is_address_complete',
        'is_water_manual_submitting',
        'ea_go_neutral',
        'hood_utm_source',
        'hood_utm_content',
        'hood_utm_medium',
        'hood_hss_channel',
        'sumo_uuid',
        'is_email_marketing',
        'is_access_require',
        'is_gas_life_support',
        'is_any_unrestrained_animal',
        'concession_card_type',
        'concession_card_number',
        'concession_start_date',
        'concession_end_date',
        'additional_access_information',
        'is_power_life_support',
        'is_skip_hubspot',
        'is_running_submission',
        'app_close_reason_id',
        'is_duplicate',
        'duplicate_group_id'
    ];


    const STATUS_UNASSIGNED = 1;
    const STATUS_ASSIGNED = 2;
    const STATUS_ESCALATED = 3;
    const STATUS_SUBMITTED = 4;
    const STATUS_ACCEPTED = 5; #todo: check
    const STATUS_REJECTED = 6; //non payable
    const STATUS_EA_PROCESSINF = 7;
    const STATUS_CLOSED = 8;

    const HAS_SOLAR = 1;
    const NO_SOLAR = 2;


    const MY_APPLICATIONS = 'my_applications';

    /**
     * do not use this anymore, use ApplicationStatusFilterMapper instead.
     *
     * @deprecated
     */
    const STATUS_MAPPING = [
        'unassigned' => self::STATUS_UNASSIGNED,
        'assigned' => self::STATUS_ASSIGNED,
        'escalated' => self::STATUS_ESCALATED,
        'submitted' => self::STATUS_SUBMITTED,
        'accepted' => self::STATUS_ACCEPTED,
        'rejected' => self::STATUS_REJECTED,
        'processing' => self::STATUS_EA_PROCESSINF,
        'closed' => self::STATUS_CLOSED,
    ];

    const PLAN_TYPE_TOTAL = 'total_plan';
    const PLAN_TYPE_BASIC = 'basic_plan';
    const PLAN_TYPE_NO_FRILLS = 'no_frills';
    const PLAN_TYPE_FLEXI_PLAN = 'flexi_plan';

    const PLAN_TYPE_ORIGIN_GO = 'origin_go';
    const PLAN_TYPE_ORIGIN_VARIABLE = 'origin_go_variable';
    const PLAN_TYPE_ORIGIN_BASIC = 'origin_basic';
    const PLAN_TYPE_ORIGIN_HOME_ASSIST = 'origin_home_assist';
    const PLAN_TYPE_ORIGIN_ADVANTAGE_VARIABLE = 'origin_advantage_variable';

    const PLAN_TYPE_SUMO_SAVER = 'sumo_saver';
    const PLAN_TYPE_SUMO_ASSURE = 'sumo_assure';
    const PLAN_TYPE_SUMO_SELECT = 'sumo_select';



    const PLAN_TYPE_TOTAL_INDEX = 1;
    const PLAN_TYPE_BASIC_INDEX = 2;
    const PLAN_TYPE_NO_FRILLS_INDEX = 3;

    const PLAN_TYPE_ORIGIN_GO_INDEX = 4;
    const PLAN_TYPE_ORIGIN_VARIABLE_INDEX = 5;
    const PLAN_TYPE_ORIGIN_BASIC_INDEX = 6;
    const PLAN_TYPE_ORIGIN_HOME_ASSIST_INDEX = 10;
    const PLAN_TYPE_ORIGIN_ADVANTAGE_VARIABLE_INDEX = 11;


    const PLAN_TYPE_SUMO_SAVER_INDEX = 7;
    const PLAN_TYPE_SUMO_ASSURE_INDEX = 8;
    const PLAN_TYPE_SUMO_SELECT_INDEX = 9;



    const SOURCE_ALL = 3;
    const SOURCE_HOOD = 0;
    const SOURCE_FOXIE = 1;
    const SOURCE_IGNITE = 2;
    const SOURCE_OUR_PROPERTY = 4;
    const SOURCE_PROPERTY_ME = 5;
    const SOURCE_HOOD_LEAD = 10;
    const SOURCE_T_APP = 11;

    const EMAIL_BILLING_EMAIL = 1;
    const EMAIL_BILLING_PAPER = 2;


    const TENANCY_TYPE_RENTER = 1;
    const TENANCY_TYPE_HOME_OWNER = 2;

    const TRIAGE = 1;

    const PROPERTY_TYPE_RESIDENTIAL = 1;
    const PROPERTY_TYPE_BUSINESS = 2;

    const PHONE_TYPE_MOBILE = 1;
    const PHONE_TYPE_HOMEPHONE = 2;

    const LEAD_SUBMIT_TYPE_ENERGY = 'energy';
    const LEAD_SUBMIT_TYPE_POWER = 'power';
    const LEAD_SUBMIT_TYPE_GAS = 'gas';
    const LEAD_SUBMIT_TYPE_WATER = 'water';

    const PROPERTY_TYPE_MAPPING = [
        'residential' => self::TENANCY_TYPE_RENTER,
        'business' => self::TENANCY_TYPE_HOME_OWNER
    ];

    const TENANCY_MAPPING = [
        'renter' => self::TENANCY_TYPE_RENTER,
        'home_owner' => self::TENANCY_TYPE_HOME_OWNER
    ];

    const TRIAGE_MAPPING = [
        'triage' => self::TRIAGE
    ];

    const TENANCY_NAME_MAPPING = [
        self::TENANCY_TYPE_RENTER => 'Renter',
        self::TENANCY_TYPE_HOME_OWNER => 'Owner'
    ];

    const SOURCE_MAPPING = [
        'all' => self::SOURCE_ALL,
        'hood' => self::SOURCE_HOOD,
        'foxie' => self::SOURCE_FOXIE,
        'ignite' => self::SOURCE_IGNITE,
        'our-property' => self::SOURCE_OUR_PROPERTY,
        'property_me' => self::SOURCE_PROPERTY_ME,
        'hood_ai' => self::SOURCE_HOOD_LEAD,
        't_app' => self::SOURCE_T_APP,
    ];

    const PLAN_TYPE_MAPPER = [
        self::PLAN_TYPE_BASIC => 2,
        self::PLAN_TYPE_NO_FRILLS => 3,
        self::PLAN_TYPE_TOTAL => 1,
        self::PLAN_TYPE_ORIGIN_GO => 4,
        self::PLAN_TYPE_ORIGIN_VARIABLE => 5,
        self::PLAN_TYPE_ORIGIN_BASIC => 6,
        self::PLAN_TYPE_ORIGIN_HOME_ASSIST => 10,
        self::PLAN_TYPE_ORIGIN_ADVANTAGE_VARIABLE => 11,
        self::PLAN_TYPE_SUMO_SAVER => 7,
        self::PLAN_TYPE_SUMO_ASSURE => 8,
        self::PLAN_TYPE_SUMO_SELECT => 9
    ];

    const SOURCE_NAME_MAPPING = [
        self::SOURCE_HOOD => 'Hood',
        self::SOURCE_FOXIE => 'Foxie',
        self::SOURCE_IGNITE => 'Ignite',
        self::SOURCE_OUR_PROPERTY => 'Ourproperty',
        self::SOURCE_PROPERTY_ME => 'Propertyme',
        self::SOURCE_HOOD_LEAD => "Hood.ai",
        self::SOURCE_T_APP => "tApp",
    ];

    const PLAN_TYPE_REVERSE_MAPPER = [
        self::PLAN_TYPE_TOTAL_INDEX => self::PLAN_TYPE_TOTAL,
        self::PLAN_TYPE_BASIC_INDEX => self::PLAN_TYPE_BASIC,
        self::PLAN_TYPE_NO_FRILLS_INDEX => self::PLAN_TYPE_NO_FRILLS,
        self::PLAN_TYPE_ORIGIN_GO_INDEX => self::PLAN_TYPE_ORIGIN_GO,
        self::PLAN_TYPE_ORIGIN_VARIABLE_INDEX =>  self::PLAN_TYPE_ORIGIN_VARIABLE,
        self::PLAN_TYPE_ORIGIN_BASIC_INDEX => self::PLAN_TYPE_ORIGIN_BASIC,
        self::PLAN_TYPE_ORIGIN_HOME_ASSIST_INDEX => self::PLAN_TYPE_ORIGIN_HOME_ASSIST,
        self::PLAN_TYPE_ORIGIN_ADVANTAGE_VARIABLE_INDEX => self::PLAN_TYPE_ORIGIN_ADVANTAGE_VARIABLE,
        self::PLAN_TYPE_SUMO_SAVER_INDEX => self::PLAN_TYPE_SUMO_SAVER,
        self::PLAN_TYPE_SUMO_ASSURE_INDEX => self::PLAN_TYPE_SUMO_ASSURE,
        self::PLAN_TYPE_SUMO_SELECT_INDEX =>  self::PLAN_TYPE_SUMO_SELECT

    ];

    const AFTER_HOUR_PAYEE_HOOD = 'hood';
    const AFTER_HOUR_PAYEE_APPLICANT = 'applicant';

    const AVAILABLE_USER_TITLES = [
        'Mr', 'Miss', 'Dr', 'Mrs', 'Ms'
    ];

    const ACCESS_ON_SITE = 'CUST ON SITE';
    const ACCESS_KEYS_METER = "KEYS IN METER BOX";
    const ACCESS_KEYS_LETTER = "KEYS IN LETTER BOX";
    const ACCESS_CUSTOMER_CONSULTATION = "Customer Consultation";

    const AVAILABLE_ADDITIONAL_INFO = [
        self::ACCESS_ON_SITE,
        self::ACCESS_KEYS_METER,
        self::ACCESS_KEYS_LETTER,
        self::ACCESS_CUSTOMER_CONSULTATION
    ];

    /**
     * @return BelongsTo
     */
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(AgentProfile::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function assignedTo()
    {
        return $this->belongsTo(AgentProfile::class, 'assigned_to');
    }

    /**
     * @return HasMany
     */
    public function connectionServices()
    {
        return $this->hasMany(ConnectionService::class);
    }

    /**
     * @return HasMany
     */
    public function tsaCallHistories()
    {
        return $this->hasMany(TSACallHistory::class , 'connection_application_id')->orderBy('attempt_id');
    }

    /**
     * @return HasOne
     */
    public function identification()
    {
        return $this->hasOne(Identification::class);
    }

    /**
     * @return HasOne
     */
    public function SugerLead()
    {
        return $this->hasOne(SugerLead::class , 'connection_application_id');
    }

    /**
     * @return HasOne
     */
    public function igniteLead()
    {
        return $this->hasOne(IgniteLead::class , 'connection_application_id');
    }

    /**
     * @return HasOne
     */
    public function ourPropertyLead()
    {
        return $this->hasOne(OurProperty::class, 'connection_application_id');
    }

    /**
     * @return HasOne
     */
    public function tApp()
    {
        return $this->hasOne(TApp::class, 'connection_application_id');
    }

    public function propertyMeLead()
    {
        return $this->hasOne(PropertyMeLead::class , 'connection_application_id');
    }

    /**
     * @return HasOne
     */
    public function authorizedPerson()
    {
        return $this->hasOne(ConnectionApplicationSecondaryACC::class , 'connection_application_id' , 'id');
    }

    /**
     * @return HasMany
     */
    public function applicationNotes()
    {
        return $this->hasMany(ApplicationNote::class);
    }


    public function getRoadType()
    {
        $data = explode(' ', $this->street_name);
        return $data[sizeof($data) - 1];
    }

    public function getbillingRoadType()
    {
        $data = explode(' ', $this->billing_street_name);
        return $data[sizeof($data) - 1];
    }


    /**
     * saving fast connect customer ref
     *
     * @param $ref
     * @param $applicationId
     */
    public static function saveFasConnectRef($applicationId, $ref)
    {
        self::query()
            ->where('id', $applicationId)
            ->update(['fast_connect_customer_reference' => $ref]);
    }

    public function getAgencyName()
    {
        return match ($this->source) {
            ConnectionApplication::SOURCE_HOOD,
            ConnectionApplication::SOURCE_PROPERTY_ME => $this->office?->name,
            ConnectionApplication::SOURCE_FOXIE => $this->SugerLead?->agency_name,
            ConnectionApplication::SOURCE_IGNITE => $this->igniteLead?->agency_name,
            ConnectionApplication::SOURCE_OUR_PROPERTY => $this->ourPropertyLead?->agency_name,
            ConnectionApplication::SOURCE_T_APP => $this->tApp?->agency_name,
            default => ''
        };
    }

    public function getAgentName()
    {
        return match ($this->source) {
            ConnectionApplication::SOURCE_HOOD,
            ConnectionApplication::SOURCE_PROPERTY_ME => $this->createdBy?->first_name.' '. $this->createdBy?->last_name,
            ConnectionApplication::SOURCE_FOXIE => $this->SugerLead?->agent_name,
            ConnectionApplication::SOURCE_IGNITE => $this->igniteLead?->agent_name,
            ConnectionApplication::SOURCE_OUR_PROPERTY => $this->ourPropertyLead?->agent_name,
            ConnectionApplication::SOURCE_T_APP => $this->createdBy?->first_name.' '. $this->createdBy?->last_name,
            default => ''
        };
    }

    /**
     * @return BelongsTo
     */
    public function submittedByUser()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function getAfterHourPayee()
    {
        $afterHourFlag = false;
        if(!empty($this->after_hour_payee)) {
            $afterHourFlag = true;
        }
        return $afterHourFlag;
    }

    /**
     * Converts 10-digit MIRN to 11-digit MIRN which appends checksum at the end of string
     *
     * @param string
     *
     * @return string
     */
    public function getMirnChecksumAttribute(){
        if(!empty($this->mirn) && count(str_split($this->mirn)) == 10){
            $arr = str_split($this->mirn);
            $isDouble = true;
            $totalSum = 0;

            for($i=count($arr)-1; $i>=0; $i--){
                $asciiVal = intval(ord($arr[$i]));
                if($isDouble)
                    $asciiVal *= 2;
                $isDouble = !$isDouble;
                $split  = array_map('intval', str_split($asciiVal));
                $sum = 0;
                foreach($split as $digit){
                    $sum += $digit;
                }

                $totalSum+= $sum;
            }

            $nextHighest = ceil($totalSum / 10) * 10;
            $checkSum = ($nextHighest - $totalSum) % 10;
            return $this->mirn . strval($checkSum);
        }

        return $this->mirn ?? '';

    }
}
