<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Services\AuthServices;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;

use Exception;


class AuthController extends Controller
{
    //sign up or register user
    public function register(RegisterUserRequest $request, AuthServices $AuthService)
    {
        try {
            $user = $request->validated();

            $user = $AuthService->register($user);
            if ($user) {
                return response([
                    'status' => true,
                    'data' => $user,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Registration failed', ['error_message' => $e->getMessage()]);
            return response(['status' => false, 'message' => 'Registration failed.']);
        }
    }



    public function verifyUserOTP(Request $request, AuthServices $verifyOTP){
        try{
            $data = $request->validate([
                'user_phone_num' => 'required',
                'otp' => 'required'
            ]);

            $OTP = $verifyOTP->verifyOTP($data);

            if($OTP['OTPerror']== false){

                return response()->json([
                    'status' => true,
                    'data' => $OTP
                    
                ]);
            }else if($OTP['OTPerror'] == true){
                return response()->json([
                    'type' => 'error',
                    'message' => $OTP['message'] ?? 'Invalid OTP'
                ],404);
            }
        }catch(Exception $e){
            Log::error('Error in ', ['error_message' => $e->getMessage()]);
        }

    }

    public function  verifyPhNo(Request $request, AuthServices $verifyPhNO)
    {
        $data = $request->validate([
            'email' => 'required',
        ]);
        $verifyPhNo= $verifyPhNO->verifyphNo($data['email']);
        if ($verifyPhNo) {
            return response()->json([
                'success' => true,
                'message' => 'OTP Send successfully '
            ]);
        }
    }

    //Resend OTP
    public function resendOtp(Request $request, AuthServices $reSendOTP){
      try{
        $data = $request->validate([
                'user_phone_num' => 'required',
                'id' => 'required'
            ]);
        $resendOTP = $reSendOTP->updateOtpAndLimit($data['id'], $data['user_phone_num']);

        if($resendOTP){
            return response()->json([
                'success' => true,
                'data' => $resendOTP,
                'message' => 'Resend OTP Send successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to resend OTP'
        ], 500);
      }catch(Exception $e){
        Log::error('Error in ', ['error_message' => $e->getMessage()]);
      }
    }



    // public function sendOTP(RegisterUserRequest $request, AuthServices $sendOTP){
    //     try{
    //         $user = $request->validated();
    //         $data = $sendOTP->sendOTP($user);
    //         if($data){
    //             return response([
    //                 'status' => true,
    //                 'data' => $data
    //             ]);
    //         }
    //     }catch(Exception $e){
    //            Log::error('Registration failed', ['error_message' => $e->getMessage()]);
    //            return response(['status' => false, 'message' => 'Registration failed.']);
    //     }

    // }

    // public function verifyOTP(Request $request, AuthServices $verifyOTP){
    //     try{
    //         $validatedOTP = $request->validate([
    //              'otp' => 'required|digits:6',
    //         ]);
    //         $OTP = $verifyOTP->verifyOTP($validatedOTP);
    //         if($OTP){
    //             return response([
    //                 'status' => true,
    //                 'data' => $OTP
    //             ]);
    //         }else if($OTP['OTPerror']){
    //             return [
    //                 'type' => 'error',
    //                 'message' => $OTP['message'] ?? 'Invalid OTP'
    //             ];
    //         }
    //     }catch(Exception $e){
    //         Log::error('Error in ', ['error_message' => $e->getMessage()]);
    //     }
    // }
    
    // login authenticate user
    public function authenticate(LoginUserRequest $request, AuthServices $AuthService)
    {
        try {
            $data = $request->validated();

            $user = $AuthService->authenticate($data);

            if (!Auth::attempt($user['userData'])) {
                return  response([
                    'error' => 'Invalid credentials provided'
                ]);
            }
            if(!$user['user']->is_verified){
               
                return response()->json([
                    'data'=> $user['user'],
                    'status' => false,
                    'message' => 'phone number does not verified!'
                 ],404);
            }

            $token = auth()->user()->createToken('userToken')->accessToken;

           
            return response([
                'data' => auth()->user(),
                'token' => $token,
            ]);
        }catch (Exception $e) {
            Log::error('Authentication failed', ['error_message' => $e->getMessage()]);
            return response(['status' => false, 'message' => 'Login failed.']);
        }
    }


    //logout user
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Logout failed', ['error_message' => $e->getMessage(),]);
            return response()->json([
                'status' => false,
                'message' => 'Logout failed: ' . $e->getMessage()
            ]);
        }
    }


    //forgot password
    public function submitforgotpasswordformapi(ForgotPasswordRequest $request, AuthServices $AuthService)
    {
        try {
            $data = $request->validated();
            $AuthService->submitforgotpasswordform($data, 'mail.ForgotPassword_api');
            return response()->json([
                'success' => true,
                'message' => 'We have emailed you a reset password link.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'failed to send email.',
                'error' => $e->getMessage()
            ]);
        }
    }
    // send reset password link
    public function submitresetpasswordform(ResetPasswordRequest $request, AuthServices $authService)
    {
        try {
            $data = $request->validated();
            $data['token'] = $request->token;
            $authService->submitresetpasswordform($data);

            return response()->json([
                'success' => true,
                'message' => 'Your password has been changed successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed to change password.',
                'error' => $e->getMessage()
            ]);
        }
    }

 }
