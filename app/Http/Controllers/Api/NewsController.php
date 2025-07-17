<?php

namespace App\Http\Controllers\Api;

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

    $path = $request->hasFile('image')
        ? $request->file('image')->store('berita', 'public')
        : null;

    $news = News::create([
        'user_id' => $request->user()->id,
        'title'   => $request->title,
        'content' => $request->content, // HTML dari Quill juga masuk di sini
        'image'   => $path,
        'status'  => 'pending',
    ]);

    return response()->json([
        'message' => 'Berita berhasil ditambahkan',
        'data'    => $news
    ], 201);
}
public function uploadImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
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

}
