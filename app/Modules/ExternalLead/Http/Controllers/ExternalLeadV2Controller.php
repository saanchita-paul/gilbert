<?php

namespace ExternalLead\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExternalSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ExternalLead\Http\Requests\ValidateCreateLeadRequest;
use ExternalLead\Http\Requests\ValidateCreateSourceRequest;
use ExternalLead\Services\AuthService;
use ExternalLead\Services\CreateLeadService;
use ExternalLead\Services\CreateExternalSourceService;
use App\Models\Office;
use Exception;
use ExternalLead\Models\ExternalLeadApiLog;

class ExternalLeadV2Controller extends Controller
{
    /**
     * this is the controller which will return access token.
     *
     * @param  Request $requst
     * @return JsonResponse
     * @throws Exception
     */
    public function getAccessToken(Request $request): JsonResponse
    {
        try {
            $authService = new AuthService();
            $result = $authService->generateAccessToken($request->toArray());
            return response()->json($result, 200);
        } catch (\Exception $exception) {
            $this->logException(__FUNCTION__, $exception);
            $response =  [
                'status' => 'failed',
                'message' => "email and password does not match"
            ];
            return response()->json($response, 200);
        }
    }

    public function createLead(ValidateCreateLeadRequest $request)
    {
    //    return $request->toArray();
        try {
            $externalSource = ExternalSource::where('email', $request->username ?? '')->firstOrFail();
            $service = new CreateLeadService();
            $newLead = $service->create($externalSource, $request->all());
            $code = 200;
            $response = [
                "status" => "success",
                "hood_lead_id" => $newLead->id,
                "message" => "Hood lead has been added successfully"
            ];
            if (empty($newLead->created_by)) {
                $response["status"] = "success_unidentified_agent";
                $response["message"] = "Hood lead has been added successfully. Agent does not exist please check with HOOD.";
                $code = 201;
            }
            return response($response, $code);
        } catch (\Exception $exception) {
            $exceptionArray = $this->logException(__FUNCTION__, $exception);
            $responseArray = [
                "status" => "fail",
                "message" => 'An error has occured. Please contact Hood for support'
            ];
            $dump = ExternalLeadApiLog::find($request->input('dump_id'));
            if ($dump) {
                $dump->exception_log = json_encode($exceptionArray);
                $dump->save();
                $responseArray['transaction_id'] = $dump->id;
            }
            return response($responseArray, 500);
        }
    }

    public function createSource(ValidateCreateSourceRequest $request)
    {
        try {
            $service = new CreateExternalSourceService();
            $newSource = $service->save($request->all());
            return response(['external_source_id' => $newSource->id], 200);
        } catch (\Exception $exception) {
            $exceptionArray = $this->logException(__FUNCTION__, $exception);
            return response($exceptionArray, 500);
        }
    }

    private function logException(string $functionName, \Exception $exception)
    {
        $message = $exception->getMessage();
        $trace = $exception->getTraceAsString();
        $exceptionArray = [
            'message' => $message,
            'trace' => $trace
        ];
        \Log::error(sprintf("%s:%s failed (refer context)", get_class($this), $functionName), $exceptionArray);
        return $exceptionArray;
    }
}
