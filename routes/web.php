<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth Routes (web.php এ রাখো — session + CSRF middleware চালু হবে)
Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
     ->middleware('auth:sanctum');
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
// User API (web.php এ রাখো, session চলবে)
Route::middleware('auth:sanctum')->get('/api/user', function () {
    return response()->json(auth()->user());
});
// require __DIR__.'/auth.php';
