<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordService
{

    public function __construct()
    {
    }


    /**
     *
     * @return $this
     */
    public function forgot(Request $request)
    {
        $email = $request->input('email');

        if(User::where('email', $email)->doesntExist()){
            return response()->json(['mgs' => 'This email does not exists'], 404);
        }

        $token = Str::random('40');
        try{
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
            ]);

            Mail::send('email.forgot_password', ['token' => $token], function (Message $message) use ($email){
                $message->to($email);
                $message->subject('Reset your password');
            });

            return response()->json(['success' => true, 'message' => 'Please check your email']);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

}
