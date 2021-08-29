<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\OfficeCommission
 *
 * @property int $id
 * @property int $office_id
 * @property int|null $type
 * @property string|null $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Office $office
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeCommission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfficeCommission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'office_id',
        'type',
        'rate'
    ];

    /**
     * @return BelongsTo
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
