<?php

namespace Origin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Origin\Services\GetProductInfoAPI;
use Origin\Services\ValidateAddressAPI;
use Origin\Services\CheckFuelAPI;
use Origin\Services\SubmitOrderAPI;

class OriginController extends Controller
{
    /**
     * Get product info from Origin
     * 
     * @param Request
     * inputs:
     * - option
     * 
     * @return JSON
     */
    public function getProductInfo(Request $request) : JsonResponse{
        $option = $request->option ?? null;
        $validator = Validator::make($request->all(), [
            'option' => 'required|in:'.implode(",", array_keys(GetProductInfoAPI::MAP_PRODUCT_TYPE)),
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }

        try {
            $productInfo = new GetProductInfoAPI($option);
            $response = $productInfo->fetch();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'No exisiting product from Origin'
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

    /**
     * Validate address and get address ID from Origin
     * 
     * @param Request
     * inputs:
     * - option
     * - number
     * 
     * @return JSON
     */
    public function validateAddress(Request $request) : JsonResponse{
        $option = $request->option ?? null;
        $number = $request->number ?? null;

        $validator = Validator::make($request->all(), [
            'option' => 'required|in:'.implode(",", array_keys(ValidateAddressAPI::MAP_VALIDATE_TYPE)),
            'number' => 'required|integer'
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }

        try {
            $validateAddress = new ValidateAddressAPI($option, $number);
            $response = $validateAddress->fetch();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'Address is not valid'
                ];
                response()->json($response, 400);
            }

            $response['status'] = 'success';
            $response['message'] = 'Validate Address Successful';

            return response()->json($response, 200);

        } catch (\Exception $exception) {
            Log::error( "Error in OriginController, ValidateAddress method" , [ 'message' => $exception->getMessage()]);
            Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'fail',
                'message' => "Oops please try again"
            ];
            return response()->json($response , 500);
        }
    }

    /**
     * Check fuel availability for an address
     * 
     * @param Request
     * inputs :
     * - option
     * - addressid
     * 
     * @return JSON
     */
    public function checkFuel(Request $request) : JsonResponse{
        $option = $request->option ?? null;
        $addressID = $request->addressid ?? null;

        $validator = Validator::make($request->all(), [
            'option' => 'required|in:'.implode(",", array_keys(CheckFuelAPI::MAP_CUSTOMER_TYPE)),
            'addressid' => 'required'
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }

        try {
            $checkFuel = new CheckFuelAPI($option, $addressID);
            $response = $checkFuel->fetch();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'Fuel for address is not available'
                ];
                return response()->json($response, 400);
            }

            $response['status'] = 'success';
            $response['message'] = 'Check Fuel Availability Successful';

            return response()->json($response, 200);

        } catch (\Exception $exception) {
            Log::error( "Error in OriginController, CheckFuel method" , [ 'message' => $exception->getMessage()]);
            Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'fail',
                'message' => "Oops please try again"
            ];
            return response()->json($response , 500);
        }
    }

    public function submitOrder(Request $request){
        try {
            $newOrder = new SubmitOrderAPI([]);
            $response = $newOrder->submit();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'Submit order is not available'
                ];
                return response()->json($response, 400);
            }

            $response['status'] = 'success';
            $response['message'] = 'Submit Order Successful';

            return response()->json($response, 200);

        } catch (\Exception $exception) {
            Log::error( "Error in OriginController, submitOrder method" , [ 'message' => $exception->getMessage()]);
            Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'fail',
                'message' => "Oops please try again"
            ];
            return response()->json($response , 500);
        }
    }
}
