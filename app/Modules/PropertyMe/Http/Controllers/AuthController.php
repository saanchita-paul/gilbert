<?php
namespace PropertyMe\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PropertyMe\Services\AuthService;
use PropertyMe\Services\BasePropertyMeAPI;

class AuthController extends Controller
{
    public function authorizeWithCode()
    {
        $url = AuthService::getOAuthUrl();

        return redirect($url);
    }

    public function callback(Request $request)
    {
        (new BasePropertyMeAPI())->getAccessTokenFromAuthCode($request->get('code'));
        \Cache::put('pm_connected', true);
        return redirect('/agent?d=1');
    }

}
