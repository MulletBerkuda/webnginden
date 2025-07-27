<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        // Ambil hanya berita yang sudah dipublikasikan
      $news = News::where('status', 'published')->latest()->get();


        // Kirim ke view 'admin.dashadmin'
        return view('admin.dashadmin', compact('news'));
    }
    public function manage()
{
    $news = News::with('user')->latest()->get();
    return view('admin.news_manage', compact('news'));
}

public function updateStatus(Request $request, $id)
{
    $news = News::findOrFail($id);
    $news->status = $news->status === 'published' ? 'pending' : 'published';
    $news->save();

    return back()->with('success', 'Status berita diperbarui.');
}

public function show($id)
{
    $berita = News::findOrFail($id);
    return view('admin.news_detail', compact('berita'));
}

}
