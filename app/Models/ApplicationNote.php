<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ApplicationNote
 *
 * @property int $id
 * @property int $connection_application_id
 * @property int $created_by
 * @property string|null $text
 * @property string|null $type
 * @property string|null $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ConnectionApplication $connectionApplication
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereConnectionApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApplicationNote extends Model
{
    use HasFactory;

    const ESCALATED = 'escalated';
    const CONFIRM_CONNECTION = 'confirmed_connection';
    const CLOSE_CONNECTION = 'close_connection';
    const REGULAR = 'regular';
    const SUBMITTED_CONNECTION = 'submitted_connection'; // SUBMITTED EA
    const SUBMITTED_ORIGIN = 'submitted_origin';

    const NOTETYPE = [
        'escalated' => self::ESCALATED,
        'confirm_connection' => self::CONFIRM_CONNECTION,
        'close_connection' => self::CLOSE_CONNECTION,
        'submitted_connection' => self::SUBMITTED_CONNECTION,
        'regular' => self::REGULAR,
        'submitted_origin' => self::SUBMITTED_ORIGIN,
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'connection_application_id',
        'created_by',
        'text',
        'title',
        'type',
        'user_role',
        'connection_details',
        'plan_details'
    ];

    /**
     * @return BelongsTo
     */
    public function connectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }
}
