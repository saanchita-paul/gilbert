<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Services\MRI\MriApplicationKeyService;
use Illuminate\Http\Request;

class MriOfficeController extends Controller
{
    public function getMriOffices()
    {
        try {
            $service = new MriApplicationKeyService();
            return $service->getData();
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
