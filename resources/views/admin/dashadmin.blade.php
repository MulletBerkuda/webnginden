<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak] { display: none !important; }</style>

</head>
<body class="bg-gray-100 font-sans">

<!-- ✅ Navbar -->
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

<!-- ✅ Konten Utama -->
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📋 Daftar Berita yang Sudah Dipublish</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($news as $item)
            <a href="{{ url('/berita/' . $item->id) }}"
               class="bg-white rounded-xl shadow-md hover:shadow-xl transition-transform transform hover:-translate-y-1 duration-300 overflow-hidden flex flex-col">
                <!-- Thumbnail -->
                @if ($item->thumbnail)
                    <img src="{{ $item->thumbnail }}" class="w-full h-48 object-cover" alt="Thumbnail">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                        No Image
                    </div>
                @endif

                <!-- Deskripsi -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ Str::limit($item->title, 60) }}
                        </h2>
                        <p class="text-xs text-gray-400 mt-1">{{ $item->created_at->format('d M Y') }}</p>
                    </div>
                    <p class="text-sm text-gray-700 mt-3">
                        {{ Str::limit(strip_tags($item->content), 100) }}
                    </p>
                </div>
            </a>
        @empty
            <p class="text-gray-600">Belum ada berita yang dipublikasikan.</p>
        @endforelse
    </div>
</div>

<!-- ✅ Script Logout -->
<script>
    function logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('is_admin');
        window.location.href = '/login';
    }
</script>

</body>
</html>
