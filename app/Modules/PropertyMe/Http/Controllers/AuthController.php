<?php
namespace PropertyMe\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use PropertyMe\Services\AuthService;
use PropertyMe\Services\BasePropertyMeAPI;

class AuthController extends Controller
{
    public function authorizeWithCode(Request $request)
    {
        $url = AuthService::getOAuthUrl($request->toArray());

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $officeId = json_decode($request->get('state'))?->office_id;

        $refreshToken = AuthService::getRefreshTokenFromAuthCode($request->get('code'));

        Office::linkWithPropertyMe($officeId, $refreshToken);

        return redirect('/agent?d=1');
    }

}
