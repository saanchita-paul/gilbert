<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ConnectionService
 *
 * @property int $id
 * @property int $connection_application_id
 * @property string|null $service_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ConnectionApplication $connectionApplication
 * @method static \Database\Factories\ConnectionServiceFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereConnectionApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConnectionService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'connection_application_id',
        'service_type'
    ];

    /**
     * @return BelongsTo
     */
    public function connectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
