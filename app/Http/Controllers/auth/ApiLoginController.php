<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;

class ApiLoginController extends Controller
{
    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'npk' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,user',
        ]);

        try {
            // Validasi password default
            if ($request->password !== 'avi1234!') {
                return back()->withErrors(['login' => 'Password tidak sesuai.'])->withInput($request->only('npk', 'role'));
            }

            // Ambil data dari API DarKar
           $response = Http::withToken(env('API_KEY'))
                ->timeout(30)
                ->get(env('API_DAKAR') . '/users');
                



            if (!$response->successful()) {
                Log::error('API DaKar error: ' . $response->body());
                return back()->withErrors(['login' => 'Gagal menghubungi API DaKar. Silakan coba lagi.'])->withInput($request->only('npk', 'role'));
            }

            $apiData = $response->json();
            
            // Handle different API response structures
            $users = $apiData['data'] ?? $apiData ?? [];
            
            if (empty($users)) {
                Log::warning('API DaKar returned empty data');
                return back()->withErrors(['login' => 'Data pengguna tidak tersedia di API DaKar.'])->withInput($request->only('npk', 'role'));
            }

            // Cari user berdasarkan NPK
            $darKarUser = collect($users)->firstWhere('npk', $request->npk);

            if (!$darKarUser) {
                return back()->withErrors(['login' => 'NPK tidak ditemukan di sistem DaKar.'])->withInput($request->only('npk', 'role'));
            }

            // Cek atau buat user di database lokal
            $user = $this->findOrCreateUser($darKarUser, $request->role);

            if (!$user) {
                return back()->withErrors(['login' => 'Gagal membuat akun pengguna.'])->withInput($request->only('npk', 'role'));
            }

            // Login user
            Auth::login($user, true); // remember = true

            // Log activity
            Log::info('User logged in successfully', [
                'npk' => $user->npk,
                'name' => $user->name,
                'role' => $user->role
            ]);

            // Redirect berdasarkan role
            $redirectRoute = $request->role === 'admin' ? 'admin.dashboard' : 'dashboard';
            
            return redirect()->route($redirectRoute)->with('success', 'Login berhasil! Selamat datang, ' . $user->name);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'npk' => $request->npk,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['login' => 'Terjadi kesalahan sistem. Silakan coba lagi.'])->withInput($request->only('npk', 'role'));
        }
    }

    /**
     * Find or create user in local database
     */
    private function findOrCreateUser(array $darKarUser, string $role)
    {
        try {
            $user = User::where('npk', $darKarUser['npk'])->first();

            $userData = [
                'npk' => $darKarUser['npk'],
                'name' => $darKarUser['fullname'] ?? $darKarUser['name'] ?? 'User',
                'department' => $darKarUser['department'] ?? 'Tidak diketahui',
                'role' => $role,
                'password' => Hash::make('avi1234!'),
            ];

            if (!$user) {
                // Create new user
                $user = User::create($userData);
                Log::info('New user created', ['npk' => $userData['npk'], 'name' => $userData['name']]);
            } else {
                // Update existing user data
                $updateData = [];
                
                if (empty($user->department) || $user->department !== ($darKarUser['department'] ?? 'Tidak diketahui')) {
                    $updateData['department'] = $darKarUser['department'] ?? 'Tidak diketahui';
                }
                
                if ($user->name !== ($darKarUser['fullname'] ?? $darKarUser['name'] ?? 'User')) {
                    $updateData['name'] = $darKarUser['fullname'] ?? $darKarUser['name'] ?? 'User';
                }

                if ($user->role !== $role) {
                    $updateData['role'] = $role;
                }

                if (!empty($updateData)) {
                    $user->update($updateData);
                    Log::info('User data updated', ['npk' => $user->npk, 'updates' => $updateData]);
                }
            }

            return $user;

        } catch (\Exception $e) {
            Log::error('Error creating/updating user: ' . $e->getMessage(), [
                'darkar_user' => $darKarUser,
                'role' => $role
            ]);
            return null;
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'Unknown';
        $userNpk = Auth::user()->npk ?? 'Unknown';

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', ['name' => $userName, 'npk' => $userNpk]);

        return redirect()->route('login.user')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Check API DarKar connection (untuk testing)
     */
    public function checkApiConnection()
    {
        try {
            $response = Http::withToken(env('API_KEY'))
                ->timeout(30)
                ->get(env('API_DAKAR') . '/users');
            
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'API DaKar connection successful',
                    'data_count' => count($response->json()['data'] ?? [])
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'API DaKar connection failed',
                'status_code' => $response->status()
            ], $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'API DaKar connection error: ' . $e->getMessage()
            ], 500);
        }
    }
}