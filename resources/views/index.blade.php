<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      function togglePasswordVisibility() {
        const passwordField = document.getElementById("password");
        const passwordToggle = document.getElementById("password-toggle-icon");
        if (passwordField.type === "password") {
          passwordField.type = "text";
          passwordToggle.setAttribute("d", "M2.458 10C3.732 6.943 6.617 4.75 10 4.75s6.268 2.193 7.542 5.25c-1.274 3.057-4.159 5.25-7.542 5.25S3.732 13.057 2.458 10z M10 8.75a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z");
        } else {
          passwordField.type = "password";
          passwordToggle.setAttribute("d", "M3.05 10a7.974 7.974 0 0113.9 0 7.974 7.974 0 01-13.9 0zm6.95-2a2 2 0 100 4 2 2 0 000-4z");
        }
      }
    </script>
  </head>
  <body class="bg-blue-500 flex items-center justify-center h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-sm">
      <!-- Logo -->
      <div class="flex justify-center mb-6">
        <img src="/path/to/logo.png" alt="Harsindo Ban Automotive" class="h-16" />
      </div>

      <!-- Login Title -->
      <h2 class="text-center text-2xl font-bold mb-2">Login</h2>
      <p class="text-center text-gray-500 mb-6">Masukkan Username dan Password</p>

      <!-- Form -->
      <form action="/login" method="POST" class="space-y-4">
        <!-- Username Input -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input type="text" id="username" name="username" class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>

        <!-- Password Input -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password" class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3" onclick="togglePasswordVisibility()">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path id="password-toggle-icon" d="M3.05 10a7.974 7.974 0 0113.9 0 7.974 7.974 0 01-13.9 0zm6.95-2a2 2 0 100 4 2 2 0 000-4z" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <div>
          <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:outline-none">Login</button>
        </div>
      </form>
    </div>
  </body>
</html>
