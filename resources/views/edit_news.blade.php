<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita - Portal KKN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .ql-editor {
            min-height: 200px;
        }
    </style>
</head>
<body>
    <h1>Edit Berita</h1>
    <form id="form">
        <input type="text" id="title" placeholder="Judul" value="{{ $berita->title }}" required><br><br>
        <input type="file" id="image"><br><br>

        <div id="editor">{!! $berita->content !!}</div><br>

        <button type="submit">Simpan</button>
    </form>

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link', 'image']
                ]
            }
        });

        function uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);

            return axios.post('/api/news/upload-image', formData, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => response.data.url);
        }

        quill.getModule('toolbar').addHandler('image', () => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                const url = await uploadImage(file);
                const range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', url);
            };
        });

        document.getElementById('form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const title = document.getElementById('title').value;
            const content = quill.root.innerHTML;
            const image = document.getElementById('image').files[0];
            const formData = new FormData();

            formData.append('title', title);
            formData.append('content', content);
            if (image) formData.append('image', image);

            try {
                const id = {{ $berita->id }};
                const res = await axios.post(`/api/news/${id}?_method=PUT`, formData, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        'Content-Type': 'multipart/form-data'
                    }
                });
                alert('Berita berhasil diupdate!');
                window.location.href = '/dashboard';
            } catch (err) {
                alert('Terjadi kesalahan saat update berita.');
                console.error(err);
            }
        });
    </script>
</body>
</html>
