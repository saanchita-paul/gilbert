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

        if(User::where('email', $email)->doesntExist()){
            return response()->json(['success' => true, 'message' => 'Please check your email']);
        }

        $token = Str::random('40');
        try{
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
            ]);

            info($token, [$email]);

            Notification::route('mail', $email)->notify(new ResetPasswordNotification($token));
            return response()->json(['success' => true, 'message' => 'Please check your email']);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
