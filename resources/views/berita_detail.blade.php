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
    <nav class="bg-white shadow mb-8">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center">
        
                    <a href="{{ url('/') }}" class=" inline-block font-bold bg-gray-200 text-gray-800 px-2 py-1 rounded hover:bg-gray-300">
    ← Home
</a>

            <div class="text-xl font-bold">
          
                <a href="{{ url('/') }}">Portal KKN</a>
              
            </div>
        </div>
    </nav>

    {{-- Konten Berita --}}
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-3xl font-bold mb-2">{{ $berita->title }}</h1>

        {{-- Info: Penulis, waktu, estimasi baca, dll --}}
        <div class="flex items-center justify-between text-sm text-gray-600 mb-4 flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <span class="font-semibold">{{ $berita->user->name ?? 'Admin' }}</span>
                <span class="text-green-600 text-xs font-bold">✔</span>
                <span>• {{ $berita->created_at->format('d M Y H:i') }} WIB</span>
                <span>• waktu baca {{ ceil(str_word_count(strip_tags($berita->content)) / 200) }} menit</span>
            </div>
            <div class="flex items-center space-x-4 mt-2 sm:mt-0">
              <button onclick="likeBerita({{ $berita->id }})" class="hover:text-red-600">
    ❤️ {{ $berita->likedBy->count() }}
</button>

                <a href="https://wa.me/?text={{ urlencode(url()->current()) }}" target="_blank" class="hover:text-green-600">🟢</a>
                <button onclick="copyLink()" class="hover:text-blue-600">🔗</button>
            </div>
        </div>

        @if ($berita->image)
            <img src="{{ asset('storage/' . $berita->image) }}" alt="Cover" class="w-full rounded mb-6 object-cover">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $berita->content !!}
        </div>
    </div>

    <footer class="mt-16 text-center text-sm text-gray-500">
        <p class="py-6">© {{ date('Y') }} Portal KKN</p>
    </footer>

    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href);
            alert("Link berhasil disalin!");
        }

        function likeBerita() {
            let likeEl = document.getElementById("likeCount");
            likeEl.innerText = parseInt(likeEl.innerText) + 1;
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
        // Update jumlah like kalau perlu
        location.reload();
    } catch (err) {
        alert("Kamu sudah menyukai berita ini");
        console.error(err);
    }
}

    </script>

</body>
</html>
