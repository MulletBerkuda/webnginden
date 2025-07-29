<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm mb-10">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-indigo-600">
                <a href="{{ url('/') }}">Portal KKN</a>
            </div>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Home</a>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="flex items-center justify-center min-h-screen -mt-20">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Login</h1>

            <form id="loginForm" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email"
                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password"
                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                <button type="submit"
                        class="bg-indigo-600 text-white w-full py-2 rounded-md hover:bg-indigo-700 transition-all font-semibold">
                    Login
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ url('/register') }}" class="text-indigo-600 hover:underline font-medium">Daftar di sini</a>
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
