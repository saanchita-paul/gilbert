<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
 * @property int|null $tenancy_type
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
        'last_name',
        'email',
        'phone',
        'tenancy_type',
        'dob',
        'moving_date',
        'address_unit',
        'street_address',
        'city',
        'postcode',
        'state',
        'country',
        'additional_instruction',
        'address_text',
        'is_email_billing',
        'property_type',
        'has_life_support',
        'has_solar',
        'nmi',
        'mirn',
        'is_escalated',
        'supplier',
        'plan_type',
        'status',
        'ea_sales_id',
        'unit_number',
        'street_number',
        'street_name',
        'hubspot_contact_id',
        'billing_unit_number',
        'billing_street_number',
        'billing_street_name',
        'billing_address_text',
        'billing_address_unit',
        'billing_street_address',
        'billing_city',
        'billing_postcode',
        'submitted_by'
    ];


    const STATUS_UNASSIGNED = 1;
    const STATUS_ASSIGNED = 2;
    const STATUS_ESCALATED = 3;
    const STATUS_SUBMITTED = 4;
    const STATUS_ACCEPTED = 5;
    const STATUS_REJECTED = 6; //non payable
    const STATUS_EA_PROCESSINF = 7;


    const MY_APPLICATIONS = 'my_applications';

    const STATUS_MAPPING = [
        'unassigned' => self::STATUS_UNASSIGNED,
        'assigned' => self::STATUS_ASSIGNED,
        'escalated' => self::STATUS_ESCALATED,
        'submitted' => self::STATUS_SUBMITTED,
        'accepted' => self::STATUS_ACCEPTED,
        'rejected' => self::STATUS_REJECTED,
    ];

    const PLAN_TYPE_TOTAL = 'total_plan';
    const PLAN_TYPE_BASIC = 'basic_plan';
    const PLAN_TYPE_NO_FRILLS = 'no_frills';

    const PLAN_TYPE_MAPPER = [
        self::PLAN_TYPE_BASIC => 1,
        self::PLAN_TYPE_NO_FRILLS => 2,
        self::PLAN_TYPE_TOTAL => 3
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
     * @return HasOne
     */
    public function identification()
    {
        return $this->hasOne(Identification::class);
    }

    /**
     * @return HasMany
     */
    public function applicationNotes()
    {
        return $this->hasMany(ApplicationNote::class);
    }
}
