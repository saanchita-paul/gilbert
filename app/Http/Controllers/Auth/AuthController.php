<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotRequest;
use App\Http\Requests\ResetRequest;
use App\Models\User;
use App\Services\AuthUserDetails;
use App\Services\ForgotPasswordService;
use App\Services\ResetPasswordService;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as RulesPassword;

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

    public function forgotPassword(ForgotRequest $request)
    {
        try {
            $service = new ForgotPasswordService();
            return $service= $service->forgot($request);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function resetPassword(ResetRequest $request)
    {
        try {
            $service = new ResetPasswordService();
            return $service= $service->reset($request);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
