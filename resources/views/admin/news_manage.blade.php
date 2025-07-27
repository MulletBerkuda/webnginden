<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak] { display: none !important; }</style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

{{-- ✅ Navbar --}}
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
<div class="max-w-6xl mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Manajemen Berita</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4 border">Judul</th>
                <th class="py-2 px-4 border">Penulis</th>
                <th class="py-2 px-4 border">Tanggal</th>
                <th class="py-2 px-4 border">Status</th>
                <th class="py-2 px-4 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($news as $item)
                <tr>
                    <td class="py-2 px-4 border">{{ $item->title }}</td>
                    <td class="py-2 px-4 border">{{ $item->user->name ?? 'Tidak diketahui' }}</td>
                    <td class="py-2 px-4 border">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="py-2 px-4 border text-center">{{ ucfirst($item->status) }}</td>
                    <td class="py-2 px-4 border text-center space-x-2">
                        <a href="{{ route('admin.news.show', $item->id) }}" class="text-blue-500 hover:underline">Detail</a>

                        <form action="{{ route('admin.news.updateStatus', $item->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="text-yellow-500 hover:underline">
                                {{ $item->status === 'published' ? 'Set Pending' : 'Publish' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
