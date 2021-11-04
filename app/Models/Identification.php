<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Identification
 *
 * @property int $id
 * @property int $connection_application_id
 * @property int|null $type
 * @property string|null $card_number
 * @property string|null $state
 * @property string|null $country
 * @property string|null $card_color
 * @property string|null $special_number
 * @property string|null $expire_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ConnectionApplication $connectionApplication
 * @method static \Database\Factories\IdentificationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereCardColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereConnectionApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereSpecialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Identification extends Model
{
    use HasFactory;

    const TYPE_PASSPORT = 1;
    const TYPE_MEDICARE = 2;
    const TYPE_DRIVING_LICENCE = 3;

    const MAP_TYPE = [
        self::TYPE_PASSPORT => 'passport',
        self::TYPE_MEDICARE => 'medicare',
        self::TYPE_DRIVING_LICENCE => 'driving licence',
    ];

    const TYPE_MAP = [
        'passport' => self::TYPE_PASSPORT ,
        'medicare' => self::TYPE_MEDICARE ,
        'driving_licence' => self::TYPE_DRIVING_LICENCE ,
        //different because data comming from api is different
        'drivers' => self::TYPE_DRIVING_LICENCE ,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'connection_application_id',
        'type',
        'card_number',
        'state',
        'country',
        'card_color',
        'special_number',
        'expire_date'
    ];

    /**
     * @return BelongsTo
     */
    public function connectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
