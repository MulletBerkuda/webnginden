<?php

namespace App\Http\Controllers\Api;

use DOMDocument;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
public function index(Request $request)
{
    return response()->json(
        News::where('user_id', $request->user()->id)->latest()->get()
    );
}

 public function store(Request $request)
{
    $request->validate([
        'title'   => 'required|string',
        'content' => 'required|string',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Upload gambar utama (jika ada)
    $path = $request->hasFile('image')
        ? $request->file('image')->store('berita', 'public')
        : null;

    // Ambil thumbnail dari konten HTML
    $thumbnail = null;
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // hindari error warning tag HTML
    $doc->loadHTML($request->content);
    $img = $doc->getElementsByTagName('img')->item(0);
    if ($img) {
        $thumbnail = $img->getAttribute('src');
    }

    // Simpan berita
    $news = News::create([
        'user_id'   => $request->user()->id,
        'title'     => $request->title,
        'content'   => $request->content,
        'image'     => $path,
        'thumbnail' => $thumbnail, // <-- thumbnail dari <img> pertama konten
        'status'    => 'pending',
    ]);

    return response()->json([
        'message' => 'Berita berhasil ditambahkan',
        'data'    => $news
    ], 201);
}
public function uploadImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
    ]);

    $path = $request->file('image')->store('berita', 'public');
    $url = asset('storage/' . $path);

    return response()->json(['url' => $url]);
}
public function toggleLike(Request $request, News $news)
{
    $user = $request->user();

    if ($news->likedBy()->where('user_id', $user->id)->exists()) {
        // Hapus like
        $news->likedBy()->detach($user->id);
        return response()->json(['message' => 'Unliked']);
    } else {
        // Tambah like
        $news->likedBy()->attach($user->id);
        return response()->json(['message' => 'Liked']);
    }
}
public function show($id)
{
    $news = News::findOrFail($id);
    return response()->json($news);
}

public function edit($id)
{
    $berita = News::findOrFail($id);
    return view('edit_news', compact('berita'));
}


public function update(Request $request, $id)
{
    $news = News::findOrFail($id);

    $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'status' => 'nullable|string',
    ]);

    $news->title = $request->title;
    $news->content = $request->content;
    $news->status = $request->status ?? $news->status;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('berita', 'public');
        $news->image = $path;
    }

    // Update thumbnail dari konten
    $doc = new \DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($request->content);
    $img = $doc->getElementsByTagName('img')->item(0);
    if ($img) {
        $news->thumbnail = $img->getAttribute('src');
    }

    $news->save();

    return response()->json([
        'message' => 'Berita berhasil diperbarui.',
        'data' => $news
    ]);
}

}
