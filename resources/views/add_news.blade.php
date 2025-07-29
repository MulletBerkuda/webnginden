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
<a href="{{ url('/') }}" class="inline-block font-bold bg-gray-200 text-gray-800 px-2 py-1 rounded hover:bg-gray-300">
    ← Home
</a>
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

        <div class="mb-4">
            <label class="block font-medium mb-1">Pilih Thumbnail</label>
            <div id="thumbnailOptions" class="grid grid-cols-3 gap-4"></div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim</button>

        <p id="notif" class="mt-4 text-green-600 hidden">✅ Berita berhasil ditambahkan!</p>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
  const token = localStorage.getItem('token');
  if (!token) {
    alert('❌ Anda belum login!');
  }

  const quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: [
          [{ 'header': [1, 2, 3, false] }],
          ['bold', 'italic', 'underline'],
          ['link', 'image'],
          [{ 'list': 'ordered' }, { 'list': 'bullet' }]
        ],
        handlers: {
          image: imageHandler
        }
      }
    }
  });

  // HANYA sisipkan gambar + enter setelahnya
  async function imageHandler() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.click();

    input.onchange = async () => {
      const file = input.files[0];
      if (!file) return;

     if (file.size > 5 * 1024 * 1024) {
  alert('❌ Gambar terlalu besar. Maksimal 5MB.');
  return;
}

      const formData = new FormData();
      formData.append('image', file);

      try {
        const res = await axios.post('/api/upload-image', formData, {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'multipart/form-data'
          }
        });

        const range = quill.getSelection(true);

        quill.insertEmbed(range.index, 'image', res.data.url);
        quill.insertText(range.index + 1, '\n', Quill.sources.SILENT);
        quill.setSelection(range.index + 2, Quill.sources.SILENT);

        setTimeout(() => {
          updateThumbnailOptions();
        }, 50);

      } catch (err) {
        alert('❌ Gagal mengunggah gambar.');
        console.error(err);
      }
    };
  }

  // Pilihan thumbnail berdasarkan gambar di editor
  function updateThumbnailOptions() {
    const content = quill.root.innerHTML;
    const parser = new DOMParser();
    const doc = parser.parseFromString(content, 'text/html');
    const images = Array.from(doc.querySelectorAll('img'));
    const container = document.getElementById('thumbnailOptions');

    container.innerHTML = '';

    if (images.length === 0) {
      container.innerHTML = '<p class="col-span-3 text-sm text-gray-500">Tidak ada gambar ditemukan</p>';
      return;
    }

    images.forEach((img, index) => {
      const url = img.getAttribute('src');
      const id = `thumb_${index}`;

      const option = document.createElement('label');
      option.className = 'thumb-option cursor-pointer flex flex-col items-center';
      option.innerHTML = `
        <input type="radio" name="thumbnail" value="${url}" id="${id}" ${index === 0 ? 'checked' : ''}>
        <img src="${url}" class="mt-1 border border-gray-300 p-1 rounded w-32 h-auto" />
      `;

      container.appendChild(option);
    });
  }

  // Pantau perubahan isi editor
  quill.on('text-change', () => {
    updateThumbnailOptions();
  });

  // Submit form
  document.getElementById('formBerita').addEventListener('submit', async function (e) {
    e.preventDefault();

    const title = document.getElementById('title').value;
    const content = quill.root.innerHTML;
    const thumbInput = document.querySelector('input[name="thumbnail"]:checked');
    const thumbnail = thumbInput ? thumbInput.value : '';

    const formData = new FormData();
    formData.append('title', title);
    formData.append('content', content);
    formData.append('thumbnail', thumbnail);

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
      document.getElementById('thumbnailOptions').innerHTML = '';

    } catch (err) {
      alert('❌ Gagal menambahkan berita.');
      console.error(err);
    }
  });
</script>

</body>
</html>
