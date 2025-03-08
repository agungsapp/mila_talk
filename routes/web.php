<?php

use App\Livewire\Dashboard\DashboardPage;
use App\Livewire\Kelas\KelasPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});


// Route::group(function () {});
Route::get('dashboard', DashboardPage::class)->name('dashboard');
Route::get('kelas', KelasPage::class)->name('kelas');
