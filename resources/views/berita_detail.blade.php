<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $berita->title }} - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">

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

    {{-- Konten Berita --}}
    <div class="max-w-4xl mx-auto bg-white p-6 mt-6 rounded-lg shadow-md transition-all duration-300 ease-in-out hover:shadow-xl">
        <h1 class="text-3xl font-extrabold mb-4 leading-snug text-gray-900">{{ $berita->title }}</h1>

        {{-- Info: Penulis, waktu, estimasi baca --}}
        <div class="flex flex-col sm:flex-row sm:justify-between text-sm text-gray-600 mb-4 gap-2">
            <div class="flex items-center flex-wrap gap-2">
                <span class="font-semibold">{{ $berita->user->name ?? 'Admin' }}</span>
                <span class="text-green-600 text-xs font-bold">✔</span>
                <span>• {{ $berita->created_at->format('d M Y H:i') }} WIB</span>
                <span>• waktu baca {{ ceil(str_word_count(strip_tags($berita->content)) / 200) }} menit</span>
            </div>
            <div class="flex items-center gap-4 mt-2 sm:mt-0 text-xl">
                <button onclick="likeBerita({{ $berita->id }})" class="hover:text-red-600 transition duration-300 ease-in-out" title="Like">
                    ❤️ {{ $berita->likedBy->count() }}
                </button>
                <a href="https://wa.me/?text={{ urlencode(url()->current()) }}" target="_blank" class="hover:text-green-600 transition duration-300" title="Share to WhatsApp">
                    🟢
                </a>
                <button onclick="copyLink()" class="hover:text-blue-600 transition duration-300" title="Copy Link">
                    🔗
                </button>
            </div>
        </div>

        @if ($berita->image)
            <img src="{{ asset('storage/' . $berita->image) }}" alt="Cover" class="w-full h-64 object-cover rounded-lg mb-6 shadow-sm transition duration-300 hover:brightness-95">
        @endif

        <div class="prose prose-lg max-w-none text-justify leading-relaxed text-gray-800">
            {!! $berita->content !!}
        </div>
    </div>

    <footer class="mt-16 text-center text-sm text-gray-500">
        <p class="py-6">© {{ date('Y') }} Portal KKN</p>
    </footer>

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

        function copyLink() {
            navigator.clipboard.writeText(window.location.href);
            alert("Link berhasil disalin!");
        }

        async function likeBerita(newsId) {
            const token = localStorage.getItem('token');

            try {
                const res = await axios.post(`/api/news/${newsId}/like`, {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                alert(res.data.message);
                location.reload();
            } catch (err) {
                alert("Kamu sudah menyukai berita ini");
                console.error(err);
            }
        }
    </script>

</body>
</html>
