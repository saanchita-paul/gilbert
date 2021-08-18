<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\HoodProfile
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $profile_photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoodProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HoodProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'profile_photo'
    ];

    /**
     * @return MorphOne
     */
    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }
}
