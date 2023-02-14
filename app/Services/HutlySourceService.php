<?php

namespace App\Services;

use App\Models\Office;

class HutlySourceService
{
    public function getSource()
    {
        return Office::query()->get();
    }
}
