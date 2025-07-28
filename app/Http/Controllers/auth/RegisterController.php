<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ticketing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman register.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'npk' => 'required|string|max:20|unique:users',
        'department' => 'required|string|max:100',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Simpan data ke database dengan Eloquent
    User::create([
        'name' => $request->name,
        'npk' => $request->npk,
        'department' => $request->department,
        'password' => Hash::make($request->password),
    ]);

    // Redirect ke halaman login dengan notifikasi
    return redirect()->route('login')->with('success', 'Registration successful. Please login.');
}
}
