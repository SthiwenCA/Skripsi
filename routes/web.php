<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// BISA DIAKSES TANPA LOGIN (Halaman Utama langsung ke Map)
Route::get('/', function () {
    return view('map');
})->name('home');

// BISA DIAKSES TANPA LOGIN (Rute Map)
Route::get('/map', function () {
    return view('map');
})->name('map');

// HARUS LOGIN (Halaman Dashboard bawaan)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// HARUS LOGIN (Pengaturan Profil)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';