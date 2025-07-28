<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketingApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

Route::get('/ticketings', [TicketingApiController::class, 'index']);
Route::get('/ticketings/{id}', [TicketingApiController::class, 'show']);
Route::post('/ticketings', [TicketingApiController::class, 'store']);
Route::put('/ticketings/{id}', [TicketingApiController::class, 'update']);
Route::delete('/ticketings/{id}', [TicketingApiController::class, 'destroy']);
});
