<?php

namespace App\Models;

use Database\Factories\MRI\MriApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MriApplication extends Model
{
    use HasFactory;

    public const NOTES_INSERT_KEY = 'HOOD_DATA';
    public const CONNECTION_APPLICATION_FIELDS = [
        'DATE_OF_BIRTH' => 'dob',
    ];
    public const IDENTIFICATION_FIELDS = [
        'PASSPORT_NUMBER' => 'card_number',
        'PASSPORT_COUNTRY' => 'country',
        'PASSPORT_EXPIRY_DATE' => 'expire_date',
        'DRIVERS_LICENSE_NUMBER' => 'card_number',
        'DRIVERS_LICENSE_STATE' => 'state',
        'DRIVERS_LICENSE_EXPIRY_DATE' => 'expire_date',
        'MEDICARE_CARD_NUMBER' => 'card_number',
        'MEDICARE_SPECIAL_NUMBER' => 'special_number',
        'MEDICARE_EXPIRY_DATE' => 'expire_date',
        'MEDICARE_CARD_COLOUR' => 'card_color',
    ];
    public const IDENTIFICATION_TYPE = [
        'PASSPORT_NUMBER' => Identification::TYPE_PASSPORT,
        'DRIVERS_LICENSE_NUMBER' => Identification::TYPE_DRIVING_LICENCE,
        'MEDICARE_CARD_NUMBER' => Identification::TYPE_MEDICARE,
    ];


    protected $casts = ['has_process_note' => 'boolean'];

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

    public function mriNoteData()
    {
        return $this->hasOne(MriNote::class)->ofMany([
            'id' => 'max',
        ], function ($query) {
                $query->where('description', 'LIKE', '%' . self::NOTES_INSERT_KEY . '%');
        });
    }

    protected static function newFactory()
    {
        return MriApplicationFactory::new();
    }
}
