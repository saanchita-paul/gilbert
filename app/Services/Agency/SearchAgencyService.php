<?php

namespace App\Services\Agency;

use App\Models\Agency;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchAgencyService
{
    /**
     * @return LengthAwarePaginator
     */
    public function get(): LengthAwarePaginator
    {
        return Agency::query()
            ->withCount('offices')
            ->paginate(12);
    }

}
