<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landing;
use App\Models\News;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\NewsController as NewsApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/admin', [NewsController::class, 'index']);
Route::get('/', function () {
    $news = News::where('status', 'published')->latest()->get();
    return view('landing', compact('news'));
});
Route::get('/berita/{id}', function ($id) {
    $berita = News::findOrFail($id);
    return view('berita_detail', compact('berita'));
});
Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register'); // nanti kita buat juga
Route::view('/landing', 'landing')->middleware('auth');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/add_news', 'add_news')->name('news.add');
 // halaman edit
Route::put('/news/{id}', [NewsApiController::class, 'update'])->name('news.update'); // proses update
Route::get('/berita/{id}/edit', [NewsApiController::class, 'edit'])->name('news.edit');


Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
Route::put('/admin/users/{id}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
Route::get('/admin/news', [NewsController::class, 'manage'])->name('admin.news.manage');
Route::post('/admin/news/{id}/status', [NewsController::class, 'updateStatus'])->name('admin.news.updateStatus');
Route::get('/admin/news/{id}', [NewsController::class, 'show'])->name('admin.news.show');
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
Route::post('/admin/news/update-status/{id}', [NewsController::class, 'updateStatus'])->name('admin.news.updateStatus');
Route::delete('/admin/news/delete/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

