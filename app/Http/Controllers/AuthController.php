<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Services\AuthServices;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;

use Exception;


class AuthController extends Controller
{


    public function register(RegisterUserRequest $request, AuthServices $AuthService)
    {

        try {

            $user = $request->validated();

         $AuthService->register($user);
             

            // Auth::login($user);

            return redirect()->route('login');
        } catch (Exception $e) {
            Log::error('Failed to fetch marks data', [
                'error_message' => $e->getMessage()
            ]);
            return back()->with('error', 'Registration  Failed' . $e->getMessage());
        }
    }

    public function authenticate(LoginUserRequest $request, AuthServices $AuthService)
    {
        try {

            $data = $request->validated();

            $user = $AuthService->authenticate($data);

            if (Auth::attempt($user)) {
                return redirect()->route('getStudentData')->with('success', 'Logged in successfully!');
            } else {
                return back()->withErrors([
                    'name' => 'Invalid credentials provided.',
                ]);
            }
        } catch (Exception $e) {
            return back()->with('error', 'Login Failed' . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return redirect()->route('login');
        } catch (Exception $e) {
            return back()->with('error', 'Logout Failed' . $e->getMessage());
        }
    }

    public function showforgotpasswordform()
    {
        return view('auth.ForgotPassword');
    }

    public function submitforgotpasswordform(ForgotPasswordRequest $request , AuthServices $AuthService)
    {
        try{
        $data=$request->validated();

        $AuthService->submitforgotpasswordform($data , 'mail.ForgotPassword');

        return back()->with('message', 'We have emailed you a reset password link');
    }catch(Exception $e){
            return back()->with('error', 'Failed to send reset password link' . $e->getMessage());
        }
    }

    public function showresetpasswordform($token)
    {
        return view('auth.ForgotPasswordLink', ['token' => $token]);
    }

    public function submitresetpasswordform(ResetPasswordRequest $request, AuthServices $AuthService)
         {
            try{
        $data= $request->validated();
        $data['token'] = $request->token;
        $AuthService->submitresetpasswordform($data);
        return redirect()->route('login')->with('success', 'your password has been changed');
            }catch(Exception $e){
            return back()->with('error', 'Failed to change password'. $e->getMessage());
        }
         }
    }
