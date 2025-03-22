<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KuisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


// Group API yang butuh autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Route untuk kelas (hanya bisa diakses setelah login)
    Route::get('/kelas', [KelasController::class, 'index']);
    Route::post('/kelas/{id}/daftar', [KelasController::class, 'register']);
    Route::get('/kelas/{id}', [KuisController::class, 'getKelasDetail']);
});
