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

// User API (web.php এ রাখো, session চলবে)
Route::middleware('auth:sanctum')->get('/api/user', function () {
    return response()->json(auth()->user());
});
require __DIR__.'/auth.php';
