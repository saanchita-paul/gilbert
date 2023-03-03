<?php

namespace App\Models;

use Database\Factories\MRI\MriApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'has_process_note' => 'boolean',
    ];

    public function mriProperty()
    {
        return $this->hasOne(MriProperty::class);
    }

    /**
     * Get the mri office that owns the mri application.
     */
    public function mriOffice()
    {
        return $this->belongsTo(MriOffice::class);
    }

    public function connectionApplication()
    {
        return $this->hasOne(ConnectionApplication::class);
    }

    public function mriNotes()
    {
        return $this->hasMany(MriNote::class);
    }

    protected static function newFactory()
    {
        return MriApplicationFactory::new();
    }
}
