<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    // 🔐 REGISTER
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'is_admin' => false // default user biasa
        ]);

        return response()->json(['message' => 'Register success'], 201);
    }

    // 🔐 LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // 🧭 Kirim response login lengkap, termasuk peran (admin/user)
        return response()->json([
            'message'       => 'Login berhasil',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'is_admin' => $user->is_admin,
            ],
            // opsional: bisa arahkan langsung URL berdasarkan role
            'redirect_to'   => $user->is_admin ? '/admin/news' : '/dashboard'
        ]);
    }

    // 🔐 LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    // 🔍 GET USER INFO
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
