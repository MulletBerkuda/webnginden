<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-2xl font-extrabold text-indigo-600">
          <a href="{{ url('/') }}">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo JEJAK" class="h-10">
            </a>
            </div>
            <div class="space-x-4" id="navbar-links">
                <!-- Akan diisi oleh JS -->
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-2">Selamat Datang di JEJAK</h1>
            <p class="text-lg opacity-90">Jaringan Elektronik Jendela Aktivitas Warga Kelurahan Nginden Jangkungan</p>
        </div>
    </section>

    <!-- Daftar Berita -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Berita Kegiatan Warga</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($news as $item)
                <a href="{{ url('/berita/' . $item->id) }}" class="bg-white rounded-xl shadow-lg overflow-hidden hover:scale-105 transform transition duration-300 hover:shadow-2xl">
                    @if ($item->thumbnail)
                        <img src="{{ asset($item->thumbnail) }}" alt="Thumbnail" class="w-full h-52 object-cover">
                    @endif
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-indigo-700">{{ Str::limit($item->title, 60) }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $item->created_at->format('d M Y') }}</p>
                        <p class="text-gray-700 mt-3">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                    </div>
                </a>
            @empty
                <p class="text-gray-600 col-span-3 text-center">Belum ada berita yang dipublikasikan.</p>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-6 mt-12 border-t">
        <div class="text-center text-gray-500 text-sm">
            &copy; 2025 JEJAK. All rights reserved.
        </div>
    </footer>

    <!-- Script Auth Navbar -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            const navbar = document.getElementById('navbar-links');

            if (token) {
                navbar.innerHTML = `
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Home</a>
                    <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Dashboard</a>
                    <a href="#" onclick="logout()" class="text-red-600 hover:text-red-800 font-medium">Logout</a>
                `;
            } else {
                navbar.innerHTML = `
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Home</a>
                    <a href="{{ url('/login') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Login</a>
                    <a href="{{ url('/register') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Register</a>
                `;
            }
        });

        function logout() {
            const token = localStorage.getItem('token');

            axios.post('/api/logout', {}, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
            .then(() => {
                localStorage.removeItem('token');
                window.location.href = '/login';
            })
            .catch(() => {
                alert('Logout gagal');
            });
        }
    </script>
</body>
</html>
