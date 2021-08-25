<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\UserInvitation
 *
 * @property int $id
 * @property int $token_code
 * @property int|null $email
 * @property string|null $user_id
 * @property string|null $status
 * @property \DateTime|null valid_till
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserInvitation $UserInvitation
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
class UserInvitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_code',
        'email',
        'user_id',
        'status',
        'valid_till'
    ];
}
