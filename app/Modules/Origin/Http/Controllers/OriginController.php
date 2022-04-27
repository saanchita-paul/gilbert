<?php

namespace Origin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Origin\Services\GetProductInfoAPI;

class OriginController extends Controller
{
    public function getProductInfo(Request $request) : JsonResponse{
        $option = $request->option ?? null;

        if(empty($option)){
            $response = [
                'status' => 'fail',
                'message' => 'Please input your option [electricity || gas]',
            ];
            response()->json($response, 300);
        }
        try {
            $productInfo = new GetProductInfoAPI($option);
            $response = $productInfo->fetch();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid option or no exisiting product from Origin'
                ];
                response()->json($response, 400);
            }

            $response['status'] = 'success';
            $response['message'] = 'Get Product Info Successful';

            return response()->json($response, 200);

        } catch (\Exception $exception) {
            Log::error( "Error in OriginController, getProductInfo method" , [ 'message' => $exception->getMessage()]);
            Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'fail',
                'message' => "Oops please try again"
            ];
            return response()->json($response , 500);
        }
    }
}
