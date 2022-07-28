<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowershopPaymentInfo extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'connection_application_id',
        'status',
        'invite_token',
        'is_active',
        'estimated_elec_billing_cost',
        'estimated_elec_billing_period',
        'estimated_gas_billing_cost',
        'estimated_gas_billing_period',

    ];



    /**
     * @return BelongsTo
     */
    public function connecttion_application()
    {
        return $this->belongsTo(ConnectionApplication::class, 'connection_application_id');
    }
}
