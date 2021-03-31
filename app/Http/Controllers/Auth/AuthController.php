<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
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

    public function authUser(Request  $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'bot_access_token' => $this->getAuthKey($user)
        ], 200);
    }

    private function getAuthKey(User $user) {
        $data = [
            'expired_at' => Carbon::now()->addMinutes((int) config('session.lifetime'))->timestamp,
            'access_key' => config('bot.access_key'),
            'user_email' => $user->email
        ];

        $crypt = new Encrypter( config('bot.encryption_key'), 'AES-128-CBC');
        return $crypt->encrypt($data, true);
    }
}
