<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class landing extends Controller
{
    public function index()
    {
        $berita = News::where('status', 'published')->latest()->get();
        return view('landing', compact('berita'));
    }
}