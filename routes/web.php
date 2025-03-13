<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('index');
})->name('home');

// Authentication routes
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');