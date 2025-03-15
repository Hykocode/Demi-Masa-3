<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Auth System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-indigo-600">AuthSystem</div>
            <div class="flex items-center">
                <span class="mr-4">Welcome, {{ Auth::user()->name }}</span>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Welcome Card -->
                <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100">
                    <h2 class="text-xl font-bold text-indigo-700 mb-2">Welcome Back!</h2>
                    <p class="text-indigo-600">You've successfully logged into your account.</p>
                </div>
                
                <!-- Account Info Card -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                    <h2 class="text-xl font-bold text-blue-700 mb-2">Account Info</h2>
                    <ul class="text-blue-600 space-y-2">
                        <li><strong>Name:</strong> {{ Auth::user()->name }}</li>
                        <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        <li><strong>Joined:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</li>
                    </ul>
                </div>
                
                <!-- Quick Actions Card -->
                <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                    <h2 class="text-xl font-bold text-green-700 mb-2">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="#" class="block text-green-600 hover:text-green-800">Edit Profile</a>
                        <a href="#" class="block text-green-600 hover:text-green-800">Change Password</a>
                        <a href="#" class="block text-green-600 hover:text-green-800">Notification Settings</a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Section -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                    <p class="text-gray-500 text-center py-4">No recent activity to display.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF Token Setup
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Handle logout
            const logoutForm = document.getElementById('logoutForm');
            
            logoutForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const response = await fetch('{{ route("logout") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        window.location.href = '{{ route("home") }}';
                    }
                } catch (error) {
                    console.error('Logout failed:', error);
                }
            });
        });
    </script>
</body>
</html>

@include('footer')