<?php

namespace Origin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OriginPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Origin\Services\GetProductInfoAPI;
use Origin\Services\ValidateAddressAPI;
use Origin\Services\CheckFuelAPI;
use Origin\Services\SubmitOrderAPI;
use Origin\Services\CheckOrderAPI;
use Origin\Services\GetPlans;
use Origin\Services\OriginService;
use Carbon\Carbon;

use App\Models\ConnectionApplication;

class OriginController extends Controller
{
    /**
     * Get list of origin plans
     */
    public function getOriginPlans(Request $request){
        $fuel_type = $request->fuel_type ?? null;
        $customer_type = $request->customer_type ?? null;

        $plans = GetPlans::getActivePlans($fuel_type, $customer_type);

        if(count($plans['plans']) > 0){
            return response()->json($plans, 200);
        }

        return response()->json('No Plans Available', 200);
    }

    /**
     * Get product info from Origin
     * 
     * @param Request
     * inputs:
     * - fuel
     * - product
     * 
     * @return JSON
     */
    public function getProductInfo(Request $request) : JsonResponse{
        $fuel = $request->fuel ?? null;
        $product = $request->product ?? null;
        
        $validator = Validator::make($request->all(), [
            'fuel' => 'required|in:'.implode(",", array_keys(GetProductInfoAPI::MAP_PRODUCT_TYPE)),
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }
        else {
            $validator = Validator::make($request->all(), [
                'product' => 'required|in:'.implode(",", array_keys(GetProductInfoAPI::MAP_PRODUCT_TYPE[$fuel])),
            ]);

            if($validator->fails()){
                $response = [
                    'status' => 'fail',
                    'message' => $validator->errors()->messages(),
                ];
                return response()->json($response, 300);
            }
        }

        try {
            $productInfo = new GetProductInfoAPI($fuel, $product);
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
     * - validateBy
     * - numberValue
     * 
     * @return JSON
     */
    public function validateAddress(Request $request) : JsonResponse{
        $validateBy = $request->validateBy ?? null;
        $numberValue = $request->numberValue ?? null;

        $validator = Validator::make($request->all(), [
            'validateBy' => 'required|in:'.implode(",", array_keys(ValidateAddressAPI::MAP_VALIDATE_TYPE)),
            'numberValue' => 'required|integer'
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }

        try {
            $validateAddress = new ValidateAddressAPI($validateBy, $numberValue);
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
     * - customerType
     * - addressID
     * 
     * @return JSON
     */
    public function checkFuel(Request $request) : JsonResponse{
        $customerType = $request->customerType ?? null;
        $addressID = $request->addressId ?? null;

        $validator = Validator::make($request->all(), [
            'customerType' => 'required|in:'.implode(",", array_keys(CheckFuelAPI::MAP_CUSTOMER_TYPE)).','.implode(",", CheckFuelAPI::MAP_CUSTOMER_TYPE),
            'addressId' => 'required'
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }

        try {
            $checkFuel = new CheckFuelAPI($customerType, $addressID);
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

    /**
     * Check order info and status after submission
     * 
     * @param Request
     * inputs :
     * - hoodReferenceNumber
     * 
     * @return JSON
     */
    public function checkOrder(Request $request) : JsonResponse{
        $partnerRefNum = $request->hoodReferenceNumber ?? null;

        $validator = Validator::make($request->all(), [
            'hoodReferenceNumber' => 'required'
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }

        try {
            $checkOrder = new CheckOrderAPI($partnerRefNum);
            $response = $checkOrder->fetch();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'Order info is not available'
                ];
                return response()->json($response, 400);
            }

            $response['status'] = 'success';
            $response['message'] = 'Check Order Info Successful';

            return response()->json($response, 200);

        } catch (\Exception $exception) {
            Log::error( "Error in OriginController, CheckOrder method" , [ 'message' => $exception->getMessage()]);
            Log::error($exception->getTraceAsString());
            $response =  [
                'status' => 'fail',
                'message' => "Oops please try again"
            ];
            return response()->json($response , 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'plan_id' => 'required',
            'connection_date' => 'required'
        ]);

        if($validator->fails()){
            $response = [
                'status' => 'fail',
                'message' => $validator->errors()->messages(),
            ];
            return response()->json($response, 300);
        }
        
        $application = $request->application_id ? ConnectionApplication::find($request->application_id) : null;
        $plan = $request->plan_id ? OriginPlan::find($request->plan_id) : null;
        $connection_date = $request->connection_date;

        try {
            if(str_contains($connection_date, 'Date')){
                $connection_date = Carbon::createFromTimestampMs($connection_date);
            }
            else {
                $connection_date = Carbon::parse($connection_date);
            }
            
            $connection_date = $connection_date->setTimezone('Australia/Melbourne')->toDateTimeLocalString();
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            $response = [
                'status' => 'fail',
                'message' => 'Invalid connection date format',
            ];
            return response()->json($response, 400);
        }

        if(!$application){
            $response = [
                'status' => 'fail',
                'message' => 'Invalid application',
            ];
            return response()->json($response, 400);
        }
        if(!$plan){
            $response = [
                'status' => 'fail',
                'message' => 'Invalid Origin plan',
            ];
            return response()->json($response, 400);
        }

        $validateBy = $plan->division_id == GetPlans::MAP_FUEL_TYPE['electricity'] ? 'nmi' : 'mirn';
        $numberValue = $validateBy == 'nmi' ? $application->nmi : $application->mirn_checksum;

        if(empty($numberValue)){
            $response = [
                'status' => 'fail',
                'message' => 'Application missing nmi or mirn value to validate address',
            ];
            return response()->json($response, 400);
        }

        try {
            // 1. validate address
            $validateAddress = new ValidateAddressAPI($validateBy, $numberValue);
            $response = $validateAddress->fetch();

            if(!$response || $response['validateStatus'] != 'Valid'){
                $response = [
                    'status' => 'fail',
                    'message' => 'Address is not valid'
                ];
                response()->json($response, 400);
            }

            $addressInfo = $response['addressInfo']; // required for submit
            $addressID = $response['addressID']; // required for submit

            // 2. validate fuel availability
            $customerType = $plan->customer_type_id;
            $checkFuel = new CheckFuelAPI($customerType, $addressID);
            $response = $checkFuel->fetch();

            if(!$response){
                $response = [
                    'status' => 'fail',
                    'message' => 'Fuel for address is not available'
                ];
                return response()->json($response, 400);
            }

            foreach($response['fuelOffers'] as $fuelOffer){
                if(strtolower($fuelOffer['fuelType']) == $plan->fuel_type && !in_array($fuelOffer['statusCode'], CheckFuelAPI::ELIGIBLE_STATUSES)){
                    $response = [
                        'status' => 'fail',
                        'message' => 'Fuel for address is not available: ' . $fuelOffer['errorReason'],
                    ];
                    return response()->json($response, 400); 
                }
            }
    
            // 3. submit order
            $data = [
                "connection" => 'move',
                "connectionDate" => $connection_date,
                "isExistingCustomer" => false,
                'isEmailBilling' => $application->is_email_billing == 1,
                'nmi_mirn' => $numberValue,
                "productInfo" => [
                    'productId' => $plan->product_id,
                    'customerTypeId' => $plan->customer_type_id,
                    'divisionId' => $plan->division_id
                ],
                "addressInfo" => [
                    'addressInfo' => $addressInfo,
                    'addressId' => $addressID
                ],
                "residentialCustomerInfo" => [
                    'title' => $application->title,
                    'firstname' => $application->first_name,
                    'lastname' => $application->last_name,
                    'dob' => Carbon::parse($application->dob)->toDateTimeLocalString(),
                    'phone' => $application->phone, // todo
                    'phonetype' => 'mobile', // todo
                    'email' => $application->email,
                ],
            ];
            
            if($application->authorizedPerson()->exists() && !empty($application->authorizedPerson->phone))
            {
                $authorized = $application->authorizedPerson;

                $data['contactPersonInfo'] = [
                    'title' => $authorized->title,
                    'firstname' => $authorized->first_name,
                    'lastname' => $authorized->last_name,
                    'dob' => Carbon::parse($authorized->dob)->toDateTimeLocalString(),
                    'phone' => $authorized->phone, // todo
                    'phonetype' => 'mobile', // todo
                    'email' => $authorized->email,
                ];
            }

            $newOrder = new SubmitOrderAPI($data);
            $errors = $newOrder->hasError();
            if($errors){
                $response = [
                    'status' => 'fail',
                    'message' => $newOrder->hasError(),
                ];
                return response()->json($response, 300);
            }

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
        } catch ( \Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
        }
    }

    public function submitOrderV2(Request $request){
        $applicationId = $request->id ?? 3427;

        $newOriginService = new OriginService($applicationId);
        $newOriginService->storeElectricity();

        return response()->json('Done', 200);

    }
}
