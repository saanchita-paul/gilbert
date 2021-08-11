<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthUserDetails;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return response()->json(['mgs' => 'success']);
        }
        return response()->json(['mgs' => 'failed'], 403);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['mgs' => 'success'], 200);
    }

    /**
     * Get auth user details
     *
     * @return JsonResponse
     */
    public function authUser(): JsonResponse
    {
        $user = (new AuthUserDetails())->toArray();
        return response()->json($user, 200);
    }
}
