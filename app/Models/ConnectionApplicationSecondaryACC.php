<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectionApplicationSecondaryACC extends Model
{
    use HasFactory;

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
