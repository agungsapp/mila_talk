<?php

use App\Livewire\Dashboard\DashboardPage;
use App\Livewire\DataMahasiswa\DataMahasiswaPage;
use App\Livewire\Kelas\KelasDetail;
use App\Livewire\Kelas\KelasPage;
use App\Livewire\Kuis\KuisCreate;
use App\Livewire\Kuis\KuisDetail;
use App\Livewire\Kuis\KuisPage;
use App\Livewire\Mahasiswa\KelasMahasiswaPage;
use App\Livewire\Soal\PreviewSoalPage;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware('auth')->group(function () {

    Route::get('dashboard', DashboardPage::class)->name('dashboard');
    Route::get('kelas', KelasPage::class)->name('kelas');
    Route::get('kelas-detail/{id}', KelasDetail::class)->name('kelas-detail');
    // kelas mahasiswa
    Route::get('kelas-mahasiswa', KelasMahasiswaPage::class)->name('kelas-mahasiswa');
    // data mahasiswa
    Route::get('data-mahasiswa', DataMahasiswaPage::class)->name('data-mahasiswa');
    // Route::get('kuis-create/{id}', KuisCreate::class)->name('kuis-create');
    Route::get('kuis', KuisPage::class)->name('kuis');
    Route::get('kuis-detail/{id}', KuisDetail::class)->name('kuis-detail');

    Route::get('preview-soal/{id}', PreviewSoalPage::class)->name('preview-soal');
});

require __DIR__ . '/auth.php';
