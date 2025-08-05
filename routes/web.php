<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\TicketingController;
use App\Http\Controllers\User\InputDataController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PrioritasController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Redirect default ke login user
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Single login endpoint (user & admin masuk sini)
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot password
Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Login Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard berdasarkan role
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/store', [UserDashboardController::class, 'store'])->name('dashboard.store');

    // Admin dashboard (khusus admin)
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('is_admin')->name('admin.dashboard');

    // Ticketing routes
    Route::get('/ticketing', [TicketingController::class, 'index'])->name('ticketing.index');
    Route::get('/ticketing/create', [TicketingController::class, 'create'])->name('ticketing.create');
    Route::post('/ticketing/{id}/prioritaskan', [TicketingController::class, 'addToPriority'])->name('ticketing.addToPriority');
    Route::get('/ticketing/{id}/show', [TicketingController::class, 'show'])->name('ticketing.show');
    Route::get('/ticketing/{id}/edit', [TicketingController::class, 'edit'])->name('ticketing.edit');
    Route::put('/ticketing/{id}', [TicketingController::class, 'update'])->name('ticketing.update');
    Route::delete('/ticketing/{id}', [TicketingController::class, 'destroy'])->name('ticketing.destroy');
    Route::patch('/ticketing/{id}/status', [TicketingController::class, 'updateStatus'])->name('ticketing.updateStatus');

    // Input data
    Route::get('/inputdata', [InputDataController::class, 'create'])->name('inputdata.create');
    Route::post('/inputdata/store', [TicketingController::class, 'store'])->name('inputdata.store');

    // Prioritas
    Route::get('/prioritas', [PrioritasController::class, 'index'])->name('prioritas.index');
    Route::delete('/prioritas/{id}', [PrioritasController::class, 'destroy'])->name('prioritas.destroy');

    // Employee
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');

});
