<?php

namespace App\Services;


use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\Models\User;

class AuthServices
{

    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'user_phone_num' => $data['user_phone_num'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);
        return $user;
    }

    //login athenticate user
    public function authenticate($request)
    {
        $userData = [
            'name' => $request['name'],
            'password' => $request['password'],
        ];
        return  $userData;
    }

    // logout user
    public function logout()
    {
        Auth::logout();
    }

    // send forgot password link to mail
    public function submitforgotpasswordform($data, $viwe)
    {

        $token = str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $data['email']],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );
        Mail::send($viwe, ['token' => $token], function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Reset Password');
        });
    }


    public function submitresetpasswordform($data)
    {
        DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();

        User::where('email', $data['email'])
            ->update(['password' => Hash::make($data['password'])]);

        DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->delete();
    }
}
