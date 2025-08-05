<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\CommonServices;

use Twilio\Rest\Client;

use App\Models\User;
use App\Models\UserOTP;

class AuthServices
{


    //Register User 
    public function register(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'user_phone_num' => $data['user_phone_num'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'is_verified' => false
        ]);

        $common = new CommonServices();
        $userOTP= $common->userOtp($user);
        
        $data = $common->sendSms($data['user_phone_num'], 'Your OTP is: ' . $userOTP['otp'] );
        
        return [
          'data' => $user,
          'userOTP'=> $userOTP,
          'message' => 'OTP sent to Your Mobile Number'
        ];
    }


    //OTP Limit
    public function updateOtpAndLimit($id,$userPhoneNo)
    {
        // $user_id = userOTP::findOrfail($id);
        $user_id = UserOTP::where('user_id', $id)->firstOrFail();
        $otp = rand(100000, 999999);

        //send SMS
        $common = new CommonServices();
        $data = $common->sendSms($userPhoneNo, "Your OTP is: $otp");

        $user_id->update([
            'otp' => $otp,
            'attempts' => 0,
            'otp_expires_at' => Carbon::now()->addMinutes(2)
        ]);
        return true;
    }


    public function verifyphNo($email){
        $user = User::where('email',$email)->firstOrFail();
        $userPhNo=$user->user_phone_num; 
        $user_id = $user->id;
        $userOtp= $this->updateOtpAndLimit($user_id, $userPhNo );
        return $userOtp;
    }


    //OTP Verification
    public function verifyOTP($data)
    {
        $user = User::where('user_phone_num', $data['user_phone_num'])->first();

        if (!$user) {
            return [
                'OTPerror' => true,
                'message' => "User Not Found"
            ];
        }

        $otpRecord = UserOTP::where('user_id', $user->id)->first();

        if (!$otpRecord) {
            return [
                'OTPerror' => true,
                'message' => 'OTP not found. Please request a new one.'
            ];
        }
        if (Carbon::now()->greaterThan($otpRecord->otp_expires_at)) {
            $this->updateOtpAndLimit($user->id, $user->user_phone_num);
            return [
                'OTPerror' => true,
                'message' => 'OTP has expired. Please request a new one.'
            ];
        }

        // checking attempts limits
        if ($otpRecord->attempts >= 10) {
            $this->updateOtpAndLimit($user->id,$user->user_phone_num);
            return [
                'OTPerror' => true,
                'message' => 'Maximum OTP attempts exceeded. A new OTP has been sent to your registered Mobile Number.'
            ];
        }
         //count attempts
        if ($otpRecord->otp != $data['otp']) {
            $otpRecord->attempts += 1;
            $otpRecord->save();

            return [
                'OTPerror' => true,
                'message' => 'Invalid OTP. Please try again.'
            ];
        }
   
        $user->update(['is_verified' => true]);

        // Delete OTP after use
        $otpRecord->delete();
     
        //validate OTP
        if ($otpRecord->otp == $data['otp']) {
        return [
            'OTPerror' => false,
            'status' => true,
            'message' => 'OTP verified successfully'
        ];

        }
    }

    //login athenticate user
    public function authenticate($request)
    {
        $userData = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];
        $userName = User::where('email', $request['email'])->first();

        return  [
            'userData' => $userData,
            'user' => $userName
        ];
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
