<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100">

{{-- ✅ Navbar (tidak diubah) --}}
<nav class="bg-white shadow sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-extrabold text-blue-600 tracking-wide">
            <a href="{{ url('/admin') }}">Admin<span class="text-gray-800">Berita</span></a>
        </div>
        <div class="space-x-4 flex items-center text-gray-700 text-sm">
            <a href="{{ url('/admin') }}" class="hover:text-blue-600 transition">Home</a>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="hover:text-blue-600 focus:outline-none">Manage</button>
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute left-0 mt-2 w-40 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20 transition-all duration-200"
                     x-transition>
                    <a href="{{ route('admin.users') }}" class="block px-4 py-2 hover:bg-gray-100">Pengguna</a>
                    <a href="{{ route('admin.news.manage') }}" class="block px-4 py-2 hover:bg-gray-100">Berita</a>
                </div>
            </div>
            <a href="#" onclick="logout()" class="text-red-500 hover:text-red-700 transition">Logout</a>
        </div>
    </div>
</nav>

{{-- ✅ Konten --}}
<div class="max-w-6xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">Manajemen Berita</h1>

    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show" 
            x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"
        >
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full bg-white border rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-3 px-4 border text-left">Judul</th>
                    <th class="py-3 px-4 border text-left">Penulis</th>
                    <th class="py-3 px-4 border text-left">Tanggal</th>
                    <th class="py-3 px-4 border text-center">Status</th>
                    <th class="py-3 px-4 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($news as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-4 border">{{ $item->title }}</td>
                        <td class="py-2 px-4 border">{{ $item->user->name ?? 'Tidak diketahui' }}</td>
                        <td class="py-2 px-4 border">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="py-2 px-4 border text-center">
                            @if($item->status === 'published')
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Published</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">Pending</span>
                            @endif
                        </td>
<td class="py-2 px-4 border text-center">
    <div class="relative inline-block text-left" x-data="{ open: false }">
        <button @click="open = !open" class="px-3 py-1 bg-gray-200 text-gray-800 text-sm rounded hover:bg-gray-300">
            Aksi
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
             class="absolute z-10 mt-2 w-36 origin-top-right bg-white border border-gray-200 rounded-md shadow-lg focus:outline-none"
             x-transition>
            <div class="py-1 text-sm text-gray-700">

                {{-- Tombol Detail --}}
                <a href="{{ route('admin.news.show', $item->id) }}"
                   class="block px-4 py-2 hover:bg-gray-100">Detail</a>

                {{-- Tombol Ubah Status --}}
                <form action="{{ route('admin.news.updateStatus', $item->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $item->status === 'published' ? 'pending' : 'published' }}">
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                        {{ $item->status === 'published' ? 'Set Pending' : 'Publish' }}
                    </button>
                </form>

                {{-- Tombol Delete --}}
                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">
                        Hapus
                    </button>
                </form>

            </div>
        </div>
    </div>
</td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ Script Logout --}}
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

    // (Opsional) Tutup dropdown jika klik di luar
    window.addEventListener('click', function(event) {
        const button = event.target.closest('button');
        const dropdown = document.getElementById('dropdownMenu');
        const isInsideDropdown = event.target.closest('#dropdownMenu');

        if (!button && !isInsideDropdown && dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
</script>

</body>
</html>
