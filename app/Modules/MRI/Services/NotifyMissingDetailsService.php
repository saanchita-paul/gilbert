<?php

namespace MRI\Services;

use App\Models\ConnectionApplication;
use App\Models\Identification;
use App\Models\MriApplication;
use Illuminate\Support\Carbon;

class NotifyMissingDetailsService
{
    /**
     * List of required fields in MRI Application Table.
     * Ensure fields with || to not be the first item
     * @param array
     */
    public const MRI_APPLICATION_REQUIRED_FIELDS = [
        'title',
        'first_name',
        'last_name',
        'email_address',
        'mobile_phone_number||home_number',
        'lease_start_date'
    ];

    /**
     * @var array int
     */
    private array $mriApplicationIds;

    public function __construct(array $mriApplicationIds = [])
    {
        $this->mriApplicationIds = $mriApplicationIds;
    }

    public function check()
    {
        $query = MriApplication::whereIn('id', $this->mriApplicationIds);
        foreach (self::MRI_APPLICATION_REQUIRED_FIELDS as $key => $field) {
            $orFields = explode("||", $field);
            if (count($orFields) > 1) {
                $query->where(function ($query) use ($orFields) {
                    foreach ($orFields as $key => $val) {
                        if ($key === array_key_first($orFields)) {
                            $query->whereNull($val);
                        } else {
                            $query->orWhereNull($val);
                        }
                    }
                });
            } else {
                if ($key === array_key_first(self::MRI_APPLICATION_REQUIRED_FIELDS)) {
                    $query->whereNull($field);
                } else {
                    $query->orWhereNull($field);
                }
            }
        }
        return $query->pluck('id')->toArray();
    }
}
