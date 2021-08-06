<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class HoodProfile extends Model
{
    use HasFactory;

    /**
     * @return MorphOne
     */
    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }
}
