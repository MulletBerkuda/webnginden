<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50">

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

                <!-- Dropdown Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="hover:text-blue-600 focus:outline-none">
                        Manage
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
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

    {{-- ✅ Konten --}}
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6 mt-6">Manajemen Pengguna</h1>

        {{-- ✅ SweetAlert + Flash Message --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                });
            </script>

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

        {{-- ✅ Tabel User --}}
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border">Nama</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Jumlah Berita</th>
                    <th class="py-2 px-4 border">Role</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="py-2 px-4 border">{{ $user->name }}</td>
                        <td class="py-2 px-4 border">{{ $user->email }}</td>
                        <td class="py-2 px-4 border text-center">{{ $user->news_count }}</td>
                        <td class="py-2 px-4 border text-center">
                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="is_admin" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>User</option>
                                    <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-2 px-4 border text-center">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ✅ Logout script --}}
    <script>
        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('is_admin');
            window.location.href = '/login';
        }
    </script>

</body>
</html>
