<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - JEJAK</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Navbar --}}
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

    {{-- Konten Dashboard --}}
    <main class="max-w-5xl mx-auto px-4 py-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Berita Saya</h1>
            <a href="{{ url('/add_news') }}"
               class="inline-block bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700 transition font-semibold shadow">
                + Tambah Berita
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
               <thead class="bg-indigo-100 text-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 text-sm font-semibold">Judul</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold">Status</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold">Tanggal</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="beritaList" class="divide-y divide-gray-200 text-sm">
                    <tr><td class="px-4 py-3" colspan="3">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </main>

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

        const token = localStorage.getItem('token');

        async function logout() {
            try {
                await axios.post('/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                localStorage.removeItem('token');
                window.location.href = '/login';
            } catch (err) {
                alert('Logout gagal');
            }
        }

        async function getMyBerita() {
            try {
                const userRes = await axios.get('/api/me', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                const userId = userRes.data.id;

                const beritaRes = await axios.get('/api/news', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                const beritaUser = beritaRes.data.filter(item => item.user_id === userId);
                const tbody = document.getElementById('beritaList');
                tbody.innerHTML = '';

                if (beritaUser.length === 0) {
                    tbody.innerHTML = `<tr><td class="px-4 py-3 text-gray-600" colspan="3">Belum ada berita.</td></tr>`;
                    return;
                }

  beritaUser.forEach(item => {
    tbody.innerHTML += `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3">${item.title}</td>
            <td class="px-4 py-3">
                <span class="text-xs font-semibold px-3 py-1 rounded-full
                    ${item.status === 'published' ? 'bg-green-100 text-green-700' : 
                      item.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                      'bg-gray-100 text-gray-700'}">
                    ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                </span>
            </td>
            <td class="px-4 py-3">${new Date(item.created_at).toLocaleDateString()}</td>
            <td class="px-4 py-3 space-x-2">
                <a href="/berita/${item.id}" class="text-indigo-600 hover:underline text-sm">Detail</a>
                <a href="/berita/${item.id}/edit" class="text-blue-600 hover:underline">Edit</a>
            </td>
        </tr>
    `;
});


            } catch (err) {
                document.getElementById('beritaList').innerHTML =
                    `<tr><td class="px-4 py-3 text-red-500" colspan="3">Gagal memuat berita.</td></tr>`;
            }
        }

        getMyBerita();
    </script>

</body>
</html>
