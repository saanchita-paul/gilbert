<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSource extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $hidden = ['password'];

    protected $guarded = [];

    public function defaultOffice()
    {
        return $this->belongsTo(Office::class, 'default_office_id');
    }

    public function connectionApplications()
    {
        return $this->hasMany('ConnectionApplication', 'external_source_id');
    }

    public function getDisplayTypeNameAttribute($value)
    {
        if (empty($value)) {
            return ucfirst($this->source_type);
        }

        return $value;
    }


    /**
     * mapping source type string value to int value.
     * Ex: property_me -> 5
     */
    public static function strToInt(?string $sourceType): ?int
    {
        $maps = Cache::rememberForever('external_sources_strtoint', function () {
            return  ExternalSource::fromCache()->mapWithKeys(fn($source) => [$source->source_type => $source->source_id]);
        });

        return $maps[$sourceType] ?? null;
    }


    /**
     * mapping source type int value string value.
     *
     * Ex: property_me -> 5
     */
    public static function intToStr(?int $sourceType): ?string
    {
        $maps = Cache::rememberForever('external_sources_inttostr', function () {
            return  ExternalSource::fromCache()->mapWithKeys(fn($source) => [$source->source_id => $source->source_type]);
        });

        return $maps[(string) $sourceType] ?? null;
    }


    /**
     * fetching sources list from cache if available,
     *
     */
    public static function fromCache(): array | Collection
    {
        return Cache::rememberForever('external_sources', function () {
            return  ExternalSource::query()
                ->select(['id', 'name', 'source_type', 'source_id', 'logo', 'table_name', 'default_office_id'])
                ->orderBy('order')
                ->where('is_active', 1)
                ->get();
        });
    }
}
