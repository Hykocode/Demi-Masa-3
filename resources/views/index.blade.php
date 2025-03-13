<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Authentication System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header >
        <div class="mx-8  py-4 flex justify-between ">
            <div class="text-3xl font-bold flex flex-row" style="color:#033473; font-family:'Maragsa'"> <img src="{{asset('images/logo.svg')}}" alt="Logo demi masa"><h3>Demi<br>Masa</h3></div>
            <button id="loginBtn" class="  px-8 py-1 rounded-full transition">Log Masuk</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="container mr-auto px-4 py-16">
        <div class="max-w-3xl mx-auto text-left">
            <h1 class="text-7xl mb-6">Paparan Info Solat Malaysia yang <div class="wave-text">
            <span>M</span>
            <span>e</span>
            <span>n</span>
            <span>a</span>
            <span>r</span>
            <span>i</span>
            <span>k</span>
            &nbsp;<span>!</span>
            </div></h1>
            
            
                <button id="registerBtn" class=" px-6 py-3 rounded-lg text-lg">Daftar Sekarang!</button>
            
        </div>
    </section>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Login</h2>
                <button class="closeModal text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="loginForm">
                <div class="mb-4">
                    <label for="loginEmail" class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="loginEmail" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="mb-6">
                    <label for="loginPassword" class="block text-gray-700 mb-2">Password</label>
                    <input type="password" id="loginPassword" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">Login</button>
            </form>
            <div class="mt-4 text-center">
                <p>Don't have an account? <button id="switchToRegister" class="text-indigo-600 hover:underline">Register</button></p>
            </div>
            <div id="loginMessage" class="mt-4 text-center hidden"></div>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Register</h2>
                <button class="closeModal text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Step 1: Email Verification -->
            <div id="registerStep1">
                <form id="emailVerificationForm">
                    <div class="mb-4">
                        <label for="registerEmail" class="block text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="registerEmail" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">Send Verification Code</button>
                </form>
            </div>
            
            <!-- Step 2: OTP Verification -->
            <div id="registerStep2" class="hidden">
                <p class="mb-4 text-gray-600">We've sent a 6-digit verification code to your email. Please enter it below:</p>
                <form id="otpVerificationForm">
                    <input type="hidden" id="otpEmail" name="email">
                    <div class="mb-4">
                        <label for="otpCode" class="block text-gray-700 mb-2">Verification Code</label>
                        <div class="flex justify-between">
                            <input type="text" id="otpCode" name="otp" maxlength="6" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">Verify</button>
                    <button type="button" id="resendOtp" class="w-full mt-2 bg-white text-indigo-600 border border-indigo-600 py-2 rounded-lg hover:bg-indigo-50 transition">Resend Code</button>
                </form>
            </div>
            
            <!-- Step 3: Registration Form -->
            <div id="registerStep3" class="hidden">
                <form id="registrationForm">
                    <input type="hidden" id="regEmail" name="email">
                    <div class="mb-4">
                        <label for="regName" class="block text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="regName" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="regPassword" class="block text-gray-700 mb-2">Password</label>
                        <input type="password" id="regPassword" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="regPasswordConfirm" class="block text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="regPasswordConfirm" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">Register</button>
                </form>
            </div>
            
            <div class="mt-4 text-center">
                <p>Already have an account? <button id="switchToLogin" class="text-indigo-600 hover:underline">Login</button></p>
            </div>
            <div id="registerMessage" class="mt-4 text-center hidden"></div>
        </div>
    </div>

    <div class="bgimg"><img src="{{asset('images/background-masjid-01.svg')}}" alt=""></div>
    @include('footer')
