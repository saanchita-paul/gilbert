<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Office
 *
 * @property int $id
 * @property int $agency_id
 * @property string|null $name
 * @property string|null $street_address
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postcode
 * @property string|null $country
 * @property string|null $abn
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency $agency
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgentProfile[] $agents
 * @property-read int|null $agents_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConnectionApplication[] $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OfficeCommission[] $officeCommissions
 * @property-read int|null $office_commissions_count
 * @method static \Database\Factories\OfficeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office query()
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereAbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Office extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agency_id',
        'name',
        'street_address',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'abn',
        'phone',
        'email',
        'hood_agent_id',
        'rent_roll',
        'property_me_refresh_token',
        'should_notify_agent',
        'is_chatbot_office'
    ];

    /**
     * @return BelongsTo
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * @return HasMany
     */
    public function agents(): HasMany
    {
        return $this->hasMany(AgentProfile::class);
    }

    public function activeAgents()
    {
        return $this->agents()->whereHas('user', function ($query) {
            $query->where('is_active', 1);
        })->count();
    }

    /**
     * @return HasMany
     */
    public function officeCommissions()
    {
        return $this->hasMany(OfficeCommission::class);
    }

    /**
     * @return HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ConnectionApplication::class);
    }

    /**
     * Saving property-me refresh token
     *
     * @param int $officeId
     * @param string $refreshToken
     *
     * @return void
     */
    public static function linkWithPropertyMe(int $officeId, string $refreshToken): void
    {
        static::where('id', $officeId)->update([
            'property_me_refresh_token' => $refreshToken,
            'property_me_client_version' => config('property_me.client_version', 'v2')
        ]);
    }


    public function eaClientCredential(): BelongsTo
    {
        return $this->belongsTo(EAClientCredential::class, 'ea_client_credential_id');
    }

    public function getEAClientId(): string
    {
        return $this->eaClientCredential?->client_id ?? config('ea.default_client_id');
    }

    public function getEAClientSecret(): string
    {
        return $this->eaClientCredential?->client_secret ?? config('ea.default_client_secret');
    }

    public function getVendorCode(): string
    {
        return $this->eaClientCredential?->vendor_code ?? config('ea.default_vendor_code');
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(OfficeAutoAssignTimeSlot::class);
    }
}
