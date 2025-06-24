<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="bg-blue-500 text-white p-4">
        <h1 class="text-lg font-bold">Dashboard</h1>
        <p>Welcome, {{ Auth::user()->name }}!</p>
    </div>
    <div class="p-6">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition">
                Logout
            </button>
        </form>
    </div>
</body>
</html>
