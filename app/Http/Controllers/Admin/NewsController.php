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



public function show($id)
{
    $berita = News::findOrFail($id);
    return view('admin.news_detail', compact('berita'));
}
public function updateStatus(Request $request, $id)
{
    $news = News::findOrFail($id);
    $news->status = $request->status;
    $news->save();

    return redirect()->back()->with('success', 'Status berita berhasil diperbarui.');
}
public function destroy($id)
{
    $news = News::findOrFail($id);
    $news->delete();

    return redirect()->back()->with('success', 'Berita berhasil dihapus.');
}
}
