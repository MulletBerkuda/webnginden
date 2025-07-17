<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Berita - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        #editor {
            height: 300px;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Tambah Berita</h1>

    <form id="formBerita">
        <div class="mb-4">
            <label class="block font-medium">Judul</label>
            <input type="text" id="title" class="w-full border border-gray-300 px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Isi Berita</label>
            <div id="editor" class="bg-white border border-gray-300 rounded"></div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim</button>

        <p id="notif" class="mt-4 text-green-600 hidden">✅ Berita berhasil ditambahkan!</p>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    const token = localStorage.getItem('token');
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'image'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }]
                ],
                handlers: {
                    image: imageHandler
                }
            }
        }
    });

    async function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await axios.post('/api/upload-image', formData, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        'Content-Type': 'multipart/form-data'
                    }
                });

                const range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', res.data.url);
            } catch (err) {
                alert('Gagal upload gambar');
            }
        };
    }

    document.getElementById('formBerita').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('title', document.getElementById('title').value);
        formData.append('content', quill.root.innerHTML);

        try {
            await axios.post('/api/news', formData, {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data'
                }
            });

            document.getElementById('notif').classList.remove('hidden');
            document.getElementById('formBerita').reset();
            quill.setContents([]);
        } catch (err) {
            alert('❌ Gagal menambahkan berita.');
            console.error(err);
        }
    });
</script>

</body>
</html>
