<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        
        // Check if email already exists (only for registration)
        if ($request->has('for_registration') && $request->for_registration) {
            $user = User::where('email', $email)->first();
            if ($user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email already registered'
                ], 422);
            }
        }
        
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Save OTP to database
        OtpVerification::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5)
            ]
        );
        
        // Send OTP email
        Mail::to($email)->send(new OtpMail($otp));
        
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully'
        ]);
    }
    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);
        
        $email = $request->email;
        $otp = $request->otp;
        
        $verification = OtpVerification::where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$verification) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP or OTP expired'
            ], 422);
        }
        
        // Mark OTP as verified
        $verification->update(['verified' => true]);
        
        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully'
        ]);
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Check if OTP is verified
        $verification = OtpVerification::where('email', $request->email)
            ->where('verified', true)
            ->first();
            
        if (!$verification) {
            return response()->json([
                'status' => false,
                'message' => 'Email not verified'
            ], 422);
        }
        
        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // Login the user
        Auth::login($user);
        
        // Delete verification entry
        $verification->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'redirect' => route('dashboard'),
            'user' => $user
        ]);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'redirect' => route('dashboard'),
                'user' => Auth::user()
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 422);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}