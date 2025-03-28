<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\JawabanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);



// Protected routes (butuh autentikasi)
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // profile
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);

    // Kelas
    Route::get('/kelas', [KelasController::class, 'index']); // Lihat semua kelas
    Route::post('/kelas/{id}/daftar', [KelasController::class, 'register']); // Siswa daftar ke kelas
    Route::get('/kelas-saya', [KelasController::class, 'myClasses']); // Siswa lihat kelas yang diikuti
    Route::get('/kelas/{id}', [KuisController::class, 'getKelasDetail']); // Detail kelas + kuis

    // Kuis
    Route::get('/kelas/{id}/kuis', [KuisController::class, 'getKuisByKelas']); // Kuis per kelas
    Route::get('/kuis/{id}', [KuisController::class, 'show']); // Detail kuis
    Route::get('/kuis/{id}/riwayat', [KuisController::class, 'riwayatPengerjaan']);
    Route::get('/kuis-progres', [KuisController::class, 'progres']);
    Route::get('/kuis-belum-lulus', [KuisController::class, 'belumLulus']);

    // Soal
    Route::get('/kuis/{id}/soal', [SoalController::class, 'getSoalByKuis']); // Soal per kuis
    Route::get('/soal/{id}', [SoalController::class, 'show']); // Detail soal

    Route::get('/kuis/{id}/soal/{nomor}', [KuisController::class, 'getSoalByNomor']);
    // Jawaban (opsional, untuk ujian)
    Route::post('/soal/{id}/jawab', [JawabanController::class, 'submitJawaban']); // Siswa submit jawaban
    Route::get('/kuis/{id}/skor', [KuisController::class, 'getSkor']);
});
