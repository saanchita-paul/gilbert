<?php


namespace App\Services\Agent;


use App\Models\ConnectionApplication;

class ApplicationService
{
    public function createApplication(array $application) {
        return ConnectionApplication::query()->updateOrCreate($application);
    }

    public function updateApplication() {

    }
}
