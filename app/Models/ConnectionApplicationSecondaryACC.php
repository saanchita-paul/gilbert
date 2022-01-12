<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectionApplicationSecondaryACC extends Model
{
    use HasFactory;

    const ENQUIRY_ONLY_STATUS = 1;
    const FULLY_AUTHORISED_STATUS = 2;
    const FINANCIALLY_RESPONSIBLE_STATUS = 3;

    const ENQUIRY_ONLY = 'enquire_only';
    const FULLY_AUTHORISED = 'fully_authorised';
    const FINANCIALLY_RESPONSIBLE = 'financially_responsible';

    const ROLE_TYPE_MAPPER = [
        self::ENQUIRY_ONLY => self::ENQUIRY_ONLY_STATUS,
        self::FULLY_AUTHORISED => self::FULLY_AUTHORISED_STATUS,
        self::FINANCIALLY_RESPONSIBLE => self::FINANCIALLY_RESPONSIBLE_STATUS
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'role',
        'dob',
        'connection_application_id',
    ];

    protected  $table = 'application_secondary_acc';

    /**
     * @return BelongsTo
     */
    public function connecttion_application()
    {
        return $this->belongsTo(ConnectionApplication::class, 'connection_application_id');
    }
}
