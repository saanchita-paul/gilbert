<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodtelPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function paymentLinks(): HasMany
    {
        return $this->hasMany(GoodtelPlanPaymentLink::class)->where('is_active', true);
    }
}
