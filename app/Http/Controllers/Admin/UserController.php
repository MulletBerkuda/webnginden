<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('news')->get(); // hitung jumlah berita tiap user (kalau relasi disiapkan)

        return view('admin.users', compact('users'));
    }

public function updateRole(Request $request, $id)
{
    $request->validate([
        'is_admin' => 'required|boolean',
    ]);

    $user = User::findOrFail($id);
    $user->is_admin = $request->is_admin;
    $user->save();

    return back()->with('success', 'Peran pengguna berhasil diperbarui.');
}
public function destroy($id)
{
    $user = User::findOrFail($id);

    // Hindari menghapus diri sendiri (opsional)
    if (auth()->id() == $user->id) {
        return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
    }

    $user->delete();

    return back()->with('success', 'User berhasil dihapus.');
}

}
