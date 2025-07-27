<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white shadow mb-8">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-xl font-bold">
                <a href="{{ url('/') }}">Portal KKN</a>
            </div>
            <div class="space-x-4" id="navbar-links">
                <!-- Akan diubah oleh JS berdasarkan token -->
            </div>
        </div>
    </nav>

    {{-- Daftar Berita --}}
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Berita Kegiatan Warga</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
     @forelse ($news as $item)
    <a href="{{ url('/berita/' . $item->id) }}" class="bg-white rounded-lg shadow hover:shadow-lg overflow-hidden transition">
        @if ($item->thumbnail)
            <img src="{{ $item->thumbnail }}" class="w-full h-48 object-cover" alt="Thumbnail">
        @endif
        <div class="p-4">
            <h2 class="text-lg font-semibold">{{ Str::limit($item->title, 60) }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $item->created_at->format('d M Y') }}</p>
            <p class="text-gray-700 mt-2">{{ Str::limit(strip_tags($item->content), 100) }}</p>
        </div>
    </a>
@empty
    <p class="text-gray-600">Belum ada berita yang dipublikasikan.</p>
@endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            const navbar = document.getElementById('navbar-links');

            if (token) {
                navbar.innerHTML = `
                    <a href="{{ url('/') }}" class="hover:underline">Home</a>
                    <a href="{{ url('/dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="#" onclick="logout()" class="hover:underline text-red-600">Logout</a>
                `;
            } else {
                navbar.innerHTML = `
                    <a href="{{ url('/') }}" class="hover:underline">Home</a>
                    <a href="{{ url('/login') }}" class="hover:underline">Login</a>
                    <a href="{{ url('/register') }}" class="hover:underline">Register</a>
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
