<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white shadow mb-8">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-xl font-bold">
                <a href="{{ url('/') }}">Portal KKN</a>
            </div>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="hover:underline">Home</a>
             
            </div>
        </div>
    </nav>

    {{-- Form Login --}}
    <div class="flex items-center justify-center min-h-screen -mt-20">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

            <form id="loginForm">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" class="w-full border px-4 py-2 rounded" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" id="password" class="w-full border px-4 py-2 rounded" required>
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700 transition">
                    Login
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ url('/register') }}" class="text-blue-600 hover:underline">Register here</a>
            </p>

            <div id="error" class="text-red-600 text-sm mt-4 text-center hidden">Email atau password salah.</div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    try {
        const response = await axios.post('/api/login', {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
        });

        localStorage.setItem('token', response.data.access_token);
        localStorage.setItem('is_admin', response.data.user.is_admin);

        if (response.data.user.is_admin) {
            window.location.href = "/admin";
        } else {
            window.location.href = "/dashboard";
        }

    } catch (error) {
        document.getElementById('error').classList.remove('hidden');
    }
});

    </script>

</body>
</html>
