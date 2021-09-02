<?php

namespace App\Http\Controllers;

use App\Services\UtilityService;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    protected  $fc_auth_url = "https://sandbox.fastconnect.net.au/oauth/token?grant_type=client_credentials&scope=datafind";
    protected  $fc_authorization = "Basic c2FuZGJveF9HcWxNejlaR3d0OE03dzdZNzdGUzV5elI6bndJQXM5WTJKR3NmVEVNVU1CU25JWWFKam4wMW9vT1hkNjZrMFhWY2E3S016SGI4";
    protected  $fc_address_url = "https://sandbox.fastconnect.net.au/api/datafind/address";
    /**
     * Getting Address from third party API
     *
     * @param Request $request
     *
     */
    public function fcAuth(Request $request)
    {
        try {

            $svcUtilities = new UtilityService();
            $authentication = $svcUtilities->fcAPIAuth($this->fc_auth_url,$this->fc_authorization);

            if($authentication == null)
            {
                return response()->json(['success' => false, 'data' => $authentication]);
            }
            return response()->json(['success' => true, 'data' => $authentication]);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function fcAddress(Request $request)
    {
        try {
            $fc_authorization = "Bearer ".$request->bearerToken();
            $body = $request->getContent();

            $svcUtilities = new UtilityService();

            $authentication = $svcUtilities->fcAPIReqAddress($this->fc_address_url, $fc_authorization, $body);

            if($authentication == null)
            {
                return response()->json(['success' => false, 'data' => $authentication]);
            }
            return response()->json(['success' => true, 'data' => $authentication]);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
