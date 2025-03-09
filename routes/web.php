<?php

use App\Livewire\Dashboard\DashboardPage;
use App\Livewire\Kelas\KelasPage;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware('auth')->group(function () {

    Route::get('dashboard', DashboardPage::class)->name('dashboard');
    Route::get('kelas', KelasPage::class)->name('kelas');
});

require __DIR__ . '/auth.php';
