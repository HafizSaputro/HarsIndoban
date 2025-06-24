<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-bengkel {
            background-image: url('images/carlos-irineu-da-costa-eMc0lpn1P60-unsplash.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"> 
</head>
<body class="bg-bengkel bg-no-repeat bg-cover bg-center min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
        <form method="POST" action="/login" autocomplete="off" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username:</label>
                <input type="text" id="username" name="username" required autocomplete="off" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <!-- Password -->
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Masukkan password Anda"
                >
                <!-- Hide/Show Button -->
                <button 
                    type="button" 
                    id="togglePassword" 
                    class="absolute right-3 top-9 text-gray-500 hover:text-gray-700">
                    👁️
                </button>
                

                <!-- Submit Button -->
            <div class="mt-3">
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Login
                </button>
            </div>
        </form>
        @if ($errors->any())
            <div class="mt-4 bg-red-100 text-red-700 border border-red-300 p-4 rounded-md">
                <strong>Error!</strong> {{ $errors->first() }}
            </div>
        @endif
    </div>

    <script>
        // JavaScript for toggling password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Change the button icon (optional)
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
