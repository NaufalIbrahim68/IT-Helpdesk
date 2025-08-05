<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'npk' => 'required',
            'password' => 'required',
        ]);

        $npk = $request->npk;
        $password = $request->password;

        $throttleKey = Str::lower($npk) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'login' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
            ]);
        }

        // Cari user lokal
        $localUser = User::where('npk', $npk)->first();

        if ($localUser && Hash::check($password, $localUser->password)) {
            Auth::login($localUser);
            RateLimiter::clear($throttleKey);
            return redirect()->route($localUser->role === 'admin' ? 'admin.dashboard' : 'dashboard');
        }

        // Jika password default, coba login via API
        if ($password === 'avi1234!') {
            $response = Http::withToken(env('API_KEY'))
                ->timeout(30)
                ->get(env('API_DAKAR') . '/users');

            if (!$response->ok()) {
                return back()->withErrors(['login' => 'Gagal menghubungi API DaKar']);
            }

            $users = $response->json()['data'];
            $darKarUser = collect($users)->firstWhere('npk', $npk);

            // Fallback khusus admin
            if (!$darKarUser && $npk === '999999') {
                $darKarUser = [
                    'npk' => '999999',
                    'fullname' => 'Admin',
                    'department' => 'IT'
                ];
            }

            if (!$darKarUser) {
                return back()->withErrors(['login' => 'NPK tidak ditemukan di sistem DaKar']);
            }

            // Simpan ke database jika belum ada
            $user = User::firstOrCreate(
                ['npk' => $darKarUser['npk']],
                [
                    'name' => $darKarUser['fullname'] ?? 'User',
                    'department' => $darKarUser['department'] ?? 'Tidak diketahui',
                    'role' => $darKarUser['npk'] === '999999' ? 'admin' : 'user',
                    'password' => bcrypt('avi1234!')
                ]
            );

            // Update department kalau kosong
            if (empty($user->department)) {
                $user->update(['department' => $darKarUser['department'] ?? 'Tidak diketahui']);
            }

            Auth::login($user);
            RateLimiter::clear($throttleKey);
            return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        return back()->withErrors(['login' => 'Login gagal. Cek kembali NPK dan password.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
