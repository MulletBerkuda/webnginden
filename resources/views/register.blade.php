<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Portal KKN</title>
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
                <a href="{{ url('/login') }}" class="hover:underline">Login</a>
            </div>
        </div>
    </nav>

    {{-- Form Register --}}
    <div class="flex items-center justify-center min-h-screen -mt-20">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

            <form id="registerForm">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium">Name</label>
                    <input type="text" id="name" class="w-full border px-4 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" class="w-full border px-4 py-2 rounded" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" id="password" class="w-full border px-4 py-2 rounded" required>
                </div>

                <button type="submit"
                        class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700 transition">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login here</a>
            </p>

            <div id="success" class="text-green-600 text-sm mt-4 text-center hidden">Register sukses! Mengarahkan ke login...</div>
            <div id="error" class="text-red-600 text-sm mt-4 text-center hidden">Gagal register. Periksa data!</div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                await axios.post('/api/register', {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                });

                document.getElementById('success').classList.remove('hidden');
                document.getElementById('error').classList.add('hidden');

                setTimeout(() => {
                    window.location.href = "/login";
                }, 1000);

            } catch (error) {
                document.getElementById('error').classList.remove('hidden');
                document.getElementById('success').classList.add('hidden');
            }
        });
    </script>

</body>
</html>
