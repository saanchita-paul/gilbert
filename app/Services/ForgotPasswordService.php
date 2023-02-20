<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\ErrorLogNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ForgotPasswordService
{

    public function __construct()
    {
    }


    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if (!$user || !$user->profile) {
            return response()->json(['success' => false, 'message' => 'User does not exists']);
        }

        $token = Str::random('40');
        $name = $user->profile?->first_name;

        try{
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
            ]);

            Notification::route('mail', $email)->notify(new ResetPasswordNotification($token, $name));
            return response()->json(['success' => true, 'message' => 'Please check your email']);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
