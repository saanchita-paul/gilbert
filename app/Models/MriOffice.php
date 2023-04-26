<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\MRI\MriOfficeFactory;

/**
 * App\Models\MriOffice
 *
 * @property int $office_id
 * @property string|null $application_id
 * @property string|null $key
 * @property string|null $company_name
 * @property string|null $activation_date
 */

class MriOffice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'office_id',
        'application_id',
        'key',
        'company_name',
        'activation_date'
    ];

    /**
     * Get the applications for the mri office.
     */
    public function mriApplications()
    {
        return $this->hasMany(MriApplication::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    protected static function newFactory()
    {
        return MriOfficeFactory::new();
    }

    public function mriLog()
    {
        return $this->belongsTo(MriLog::class);
    }
}
