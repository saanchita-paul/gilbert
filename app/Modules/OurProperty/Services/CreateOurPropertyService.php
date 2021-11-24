<?php


namespace OurProperty\Services;


use App\Models\ConnectionApplication;
use OurProperty\Models\OurProperty;

class CreateOurPropertyService
{
    public function create($requestData) {
        return OurProperty::create($requestData);
    }

    public function createConnectionApp() {
        $conn = ConnectionApplication::create([]);
    }
}
