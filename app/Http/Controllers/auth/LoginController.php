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
    public function showAdminLoginForm()
    {
        return view('auth.login', ['role' => 'admin']);
    }

    public function showUserLoginForm()
    {
        return view('auth.login', ['role' => 'user']);
    }

    public function login(Request $request)
{
     
    $request->validate([
        'npk' => 'required',
        'password' => 'required',
        'role' => 'required|in:admin,user',
    ]);

    $npk = $request->npk;
    $password = $request->password;
    $role = $request->role;

    // Deteksi NPK khusus dan ubah role jadi admin otomatis
    if ($npk === '999999') {
        $role = 'admin';
    }

    $throttleKey = Str::lower($npk) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        return back()->withErrors([
            'login' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
        ]);
    }

    // Coba cari user lokal terlebih dahulu
    $localUser = User::where('npk', $npk)->first();

    // Jika user lokal ditemukan, login langsung tanpa cek API
    if ($localUser && Hash::check($password, $localUser->password)) {
        if ($localUser->role !== $role) {
            return back()->withErrors(['login' => 'Role tidak sesuai.']);
        }

        Auth::login($localUser);
        RateLimiter::clear($throttleKey);
        return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'dashboard');
    }

    // Jika password adalah default dan user belum ditemukan → coba API
    if ($password === 'avi1234!') {
       $response = Http::withToken(env('API_KEY'))
                ->timeout(30)
                ->get(env('API_DAKAR') . '/users');


     if (!$response->ok()) {
    

            return back()->withErrors(['login' => 'Gagal menghubungi API DaKar']);
        }

        $users = $response->json()['data'];
        $darKarUser = collect($users)->firstWhere('npk', $npk);

        // Fallback manual jika NPK tidak ada di API tapi diizinkan lokal
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

        $user = User::where('npk', $darKarUser['npk'])->first();

        if (!$user) {
            $user = User::create([
                'npk' => $darKarUser['npk'],
                'name' => $darKarUser['fullname'] ?? 'User',
                'department' => $darKarUser['department'] ?? 'Tidak diketahui',
                'role' => $darKarUser['npk'] === '999999' ? 'admin' : $role,
                'password' => bcrypt('avi1234!'),
            ]);
        } elseif (empty($user->department)) {
            $user->update([
                'department' => $darKarUser['department'] ?? 'Tidak diketahui',
            ]);
        }

        

        Auth::login($user);
        RateLimiter::clear($throttleKey);
        return redirect()->route($role === 'admin' ? 'admin.dashboard' : 'dashboard');
    }

    RateLimiter::hit($throttleKey, 60);
    return back()->withErrors(['login' => 'Login gagal. Cek kembali NPK, password, dan role.']);
}

    public function logout()
    {
        Auth::logout();
        return redirect('/login/user');
    }

    protected function credentials(Request $request)
    {
        return [
            'npk' => $request->get('npk'),
            'password' => $request->get('password'),
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'user') {
            return redirect('/user/dashboard');
        }

        return redirect('/');
    }
}
