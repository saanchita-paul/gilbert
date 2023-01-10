<?php

namespace App\Http\Controllers;

use App\Services\ApplicationEventService;
use Illuminate\Http\Request;

class ApplicationEventController extends Controller
{
    /**
     * create application event
     */
    public function saveEvent(Request $request)
    {
        try {
            return (new ApplicationEventService())->saveApplicationEvent($request->toArray());
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
