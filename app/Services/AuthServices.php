<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Twilio\Rest\Client;

use App\Models\User;
use App\Models\UserOTP;

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
            'is_verified' => false
        ]);

        $otp = rand(100000, 999999);

        $userOTP = userOTP::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'attempts' => 0, //verify otp attempts
        ]);





        $contactNo = "+91" . $data['user_phone_num'];

        $twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $from = config('services.twilio.sms_from');
        $twilio->messages->create($contactNo, [
            'from' => $from,
            'body' => "Your OTP is: $otp"
        ]);




        return $user;
    }




    public function updateOtpAndLimit($id,$user_ph_no)
    {
        // $user_id = userOTP::findOrfail($id);
        $user_id = UserOTP::where('user_id', $id)->firstOrFail();
        $otp = rand(100000, 999999);

        $contactNo = "+91" .$user_ph_no;
        $twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $from = config('services.twilio.sms_from');
        $twilio->messages->create($contactNo, [
            'from' => $from,
            'body' => "Your OTP is: $otp"
        ]);

        $user_id->update([
            'otp' => $otp,
            'attempts' => 0
        ]);
        return true;
    }



    // public function verifyOTP($data)
    // {
    //     $user = User::where('user_phone_num', $data['user_phone_num'])->first();

    //     if (!$user) {
    //         return [
    //             'OTPerror' => true,
    //             'message' => "User Not Found"
    //         ];
    //     }

    //     $otpRecord = UserOTP::where('user_id', $user->id)
    //         ->where('otp', $data['otp'])
    //         ->first();
    //     }}


    public function verifyOTP($data)
    {

        // $otpRecord = UserOTP::where('user_id', $data['user_id'])
        //     ->where('otp', $data['otp'])
        //     ->first();
        // $otpRecord = UserOTP::where('user_id', $data['user_id'])->first();
        $user = User::where('user_phone_num', $data['user_phone_num'])->first();

        if (!$user) {
            return [
                'OTPerror' => true,
                'message' => "User Not Found"
            ];
        }

        $otpRecord = UserOTP::where('user_id', $user->id)->first();

        // checking attempts limits
        if ($otpRecord->attempts >= 10) {
            $this->updateOtpAndLimit($user->id,$user->user_phone_num);
            return [
                'OTPerror' => true,
                'message' => 'Maximum OTP attempts exceeded.'
            ];
        }

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

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully'
        ]);

        return [
            'OTPerror' => false
        ];
    }

    // public function sendOTP($data){

    //   try{
    //     $otp = rand(100000, 999999);
    //     Session::put('register_data',[
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'user_phone_num' => $data['user_phone_num'],
    //         'password' => $data['password'],
    //         'role_id'=>$data['role_id'],  
    //         'otp' => $otp
    //     ]);
    //      Session::save();
    //      Log::error('Full session', session()->all());
    //     $contactNo = "+91".$data['user_phone_num'];
    //     Log::error('contact_no', ['contact_no' => $contactNo]);
    //     //Twilio Send SMS
    //     $twilio = new Client(
    //       config('services.twilio.sid'),
    //       config('services.twilio.token')
    //     );
    //     $from = config('services.twilio.sms_from');
    //     $twilio->messages->create($contactNo,[
    //       'from' => $from,
    //       'body' => "Your OTP is: $otp"
    //     ]);

    //     return true;

    //   }catch(Exception $e){
    //       Log::error('error in '. $e->getMessage());
    //   }
    // }


    // public function verifyOTP($otp){
    //     $data = Session::get('register_data');
    //      Session::save();
    //      Log::error('user details', ['user' => $data]);


    //     if (!$data || !isset($data['otp'])) {
    //       Log::error('Session expired or OTP missing.');
    //       return [
    //           'OTPerror' => true,
    //           'message' => ' Session expired. Please register again.'
    //       ];
    //   }

    //     if($otp != $data['otp']){
    //         return[
    //             'OTPerror' => true,
    //             'message' => 'Invalid OTP. Please try again.'
    //         ];
    //     }

    //     $this->register($data);
    //     Session::forget('register_data');
    //     return [
    //         'OTPerror' => false
    //     ];
    // }

    //login athenticate user
    public function authenticate($request)
    {
        $userData = [
            'name' => $request['name'],
            'password' => $request['password'],
        ];
        $userName = User::where('name', $request['name'])->first();
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
