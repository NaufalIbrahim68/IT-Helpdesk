<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\TicketingController;
use App\Http\Controllers\User\InputDataController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\PrioritasController;
/*
|--------------------------------------------------------------------------
| TESTING & DEBUGGING
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/


// Login routes
Route::get('/', fn () => redirect()->route('login.user'));
// Middleware

// Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/login/user', [LoginController::class, 'showUserLoginForm'])->name('login.user');
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
Route::get('/login', fn () => redirect()->route('login.user'))->name('login');
// Route::post('/login', [ApiLoginController::class, 'login'])->name('login.custom');
Route::post('/logout', [ApiLoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', fn() => view('dashboard'))->middleware('api.auth');

Route::post('/login', [LoginController::class, 'login'])->name('login');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register (untuk user biasa)
// Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register']);

// Forgot password (untuk admin)
Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Login Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // =======================
    // ADMIN ROUTES
    // =======================
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // =======================
    // USER ROUTES
    // =======================
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/store', [UserDashboardController::class, 'store'])->name('dashboard.store');

    // =======================
    // TICKETING (shared)
    // =======================
    Route::get('/ticketing', [TicketingController::class, 'index'])->name('ticketing.index');
    Route::patch('/ticketing/{id}/update-status', [TicketingController::class, 'updateStatus'])->name('ticketing.updateStatus');

    // =======================

     // Additional ticketing routes for complete CRUD
    Route::get('/ticketing/create', [TicketingController::class, 'create'])->name('ticketing.create');
    Route::get('/ticketing/{id}/show', [TicketingController::class, 'show'])->name('ticketing.show');
    Route::get('/ticketing/{id}/edit', [TicketingController::class, 'edit'])->name('ticketing.edit');
    Route::put('/ticketing/{id}', [TicketingController::class, 'update'])->name('ticketing.update');
    Route::delete('/ticketing/{id}', [TicketingController::class, 'destroy'])->name('ticketing.destroy');
    Route::patch('/ticketing/{id}/status', [TicketingController::class, 'updateStatus'])->name('ticketing.updateStatus');
// INPUT DATA (USER ONLY)
// =======================
Route::get('/inputdata', [InputDataController::class, 'create'])->name('inputdata.create');
Route::post('/inputdata/store', [TicketingController::class, 'store'])->name('inputdata.store');

Route::get('/manajemen-user', [App\Http\Controllers\ManajemenUserController::class, 'index'])->name('manajemen-user.index');
Route::get('/prioritas', [App\Http\Controllers\PrioritasController::class, 'index'])->name('prioritas.index');



Route::post('/ticketing/{id}/prioritaskan', [TicketingController::class, 'addToPriority'])->name('ticketing.addToPriority');


Route::get('/prioritas', [PrioritasController::class, 'index'])->name('prioritas.index');
Route::delete('/prioritas/{id}', [PrioritasController::class, 'destroy'])->name('prioritas.destroy');
Route::get('/employees', [EmployeeController::class, 'index']);

});

