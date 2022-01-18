<?php
namespace PropertyMe\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use PropertyMe\Services\AuthService;
use PropertyMe\Services\BasePropertyMeAPI;

/**
 *
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function authorizeWithCode(Request $request)
    {
        $url = AuthService::getOAuthUrl($request->toArray());

        return redirect($url);
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function callback(Request $request)
    {
        $officeId = json_decode($request->get('state'))?->office_id;

        $refreshToken = AuthService::getRefreshTokenFromAuthCode($request->get('code'));

        Office::linkWithPropertyMe($officeId, $refreshToken);

        return redirect('/agent?d=1');
    }

}
