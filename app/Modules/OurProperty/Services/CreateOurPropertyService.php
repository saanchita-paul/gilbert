<?php


namespace App\Modules\OurProperty\HTTP\Services;


use App\Models\ConnectionApplication;
use App\Modules\OurProperty\Model\OurProperty;

class CreateOurPropertyService
{
    public function create($requestData) {
        return OurProperty::create($requestData);
    }

    public function createConnectionApp() {
        $conn = ConnectionApplication::create([]);
    }
}
