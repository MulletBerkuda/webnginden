<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white shadow mb-8">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-xl font-bold">
                <a href="{{ url('/') }}">Portal KKN</a>
            </div>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="hover:underline">Home</a>
                <a href="{{ url('/dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="#" onclick="logout()" class="hover:underline text-red-600">Logout</a>
            </div>
        </div>
    </nav>

    {{-- Konten Dashboard --}}
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Berita Saya</h1>

        <div class="mb-6">
            <a href="{{ url('/add_news') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Berita
            </a>
        </div>

        <table class="w-full border text-left bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2">Judul</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Tanggal</th>
                </tr>
            </thead>
            <tbody id="beritaList">
                <tr><td class="p-2" colspan="3">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <script>
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
                    tbody.innerHTML = `<tr><td class="p-2" colspan="3">Belum ada berita.</td></tr>`;
                }

                beritaUser.forEach(item => {
                    tbody.innerHTML += `
                        <tr class="border-b">
                            <td class="p-2">${item.title}</td>
                            <td class="p-2 capitalize">${item.status}</td>
                            <td class="p-2">${new Date(item.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                });

            } catch (err) {
                document.getElementById('beritaList').innerHTML =
                    `<tr><td class="p-2 text-red-500" colspan="3">Gagal memuat berita.</td></tr>`;
            }
        }

        getMyBerita();
    </script>

</body>
</html>
