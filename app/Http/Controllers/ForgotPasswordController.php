<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Simulasi: Kirim notifikasi ke email (bisa diganti dengan logika lain)
        return back()->with('status', 'Link reset password berhasil dikirim (simulasi).');
    }
}
