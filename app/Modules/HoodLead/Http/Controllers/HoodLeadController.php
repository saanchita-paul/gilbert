<?php

namespace HoodLead\Http\Controllers;

use App\Http\Controllers\Controller;
use HoodLead\Services\StoreHoodLead;
use Illuminate\Http\Request;

class HoodLeadController extends Controller
{
    public function store(Request $request)
    {
        try {
            $service = new StoreHoodLead($request->toArray());
            $leadId = $service->save();

            return response()->json([
                'success' => true,
                "lead_id" => $leadId,
                "message" => "Lead successfully saved!"
            ]);
        } catch (\Exception $exception) {
            #todo: send email to notify
            return $this->sendErrorResponse($exception);
        }
    }
}
