<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordService
{

    public function __construct()
    {
    }


    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $token = $request->input('token');

        if(!$passwordResets = DB::table('password_resets')->where('token', $token)->first()) {
            return response()->json(['mgs' => 'Invalid token'], 400);
        }

        /** @var  User $user */
        if(!$user = User::where('email', $passwordResets->email)->first()) {
            return response()->json(['mgs' => 'This email does not exists'], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();
        DB::table('password_resets')->where('token', $token)->delete();

        return response()->json(['success' => true, 'message' => 'Success']);
    }

    public function checkIsValidToken($token)
    {
       $validUser = DB::table('password_resets')->where('token', $token)->first();
       return !is_null($validUser);
    }
}
