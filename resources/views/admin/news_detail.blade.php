<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $berita->title }} - Detail Berita</title>
       <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak] { display: none !important; }</style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
<nav class="bg-white shadow sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="text-2xl font-extrabold text-blue-600 tracking-wide">
            <a href="{{ url('/admin') }}">Admin<span class="text-gray-800">Berita</span></a>
        </div>

        <!-- Navigation Links -->
        <div class="space-x-4 flex items-center text-gray-700 text-sm">
            <a href="{{ url('/admin') }}" class="hover:text-blue-600 transition">Home</a>

            <!-- ✅ Dropdown Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="hover:text-blue-600 focus:outline-none">
                    Manage
                </button>
               <div x-show="open" @click.away="open = false"
     x-cloak
     class="absolute left-0 mt-2 w-40 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 transition-all duration-200"
     x-transition>
    <a href="{{ route('admin.users') }}" class="block px-4 py-2 hover:bg-gray-100">Pengguna</a>
    <a href="{{ route('admin.news.manage') }}" class="block px-4 py-2 hover:bg-gray-100">Berita</a>
</div>

            </div>

            <!-- Logout -->
            <a href="#" onclick="logout()" class="text-red-500 hover:text-red-700 transition">Logout</a>
        </div>
    </div>
</nav>

    {{-- Konten Berita --}}
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mt-10">
        <h1 class="text-3xl font-bold mb-2">{{ $berita->title }}</h1>

        <div class="text-sm text-gray-600 mb-4">
            <span class="font-semibold">{{ $berita->user->name ?? 'Admin' }}</span>
            • {{ $berita->created_at->format('d M Y H:i') }} WIB
            • Waktu baca: {{ ceil(str_word_count(strip_tags($berita->content)) / 200) }} menit
            • Status: <span class="font-semibold">{{ ucfirst($berita->status) }}</span>
        </div>

        @if ($berita->image)
            <img src="{{ asset('storage/' . $berita->image) }}" alt="Cover" class="w-full rounded mb-6 object-cover">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $berita->content !!}
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.news.manage') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ← Kembali ke Daftar Berita
            </a>
        </div>
    </div>
<script>
    function logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('is_admin');
        window.location.href = '/login';
    }

    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    }

    // Tutup dropdown saat klik di luar area dropdown
    window.addEventListener('click', function(event) {
        const button = event.target.closest('button');
        const dropdown = document.getElementById('dropdownMenu');
        const isInsideDropdown = event.target.closest('#dropdownMenu');

        if (!button && !isInsideDropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
</script>
</body>
</html>
