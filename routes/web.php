<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// =========================================================
// 1. HALAMAN UTAMA (PUBLIC)
// =========================================================
// Bisa diakses oleh siapa saja (Guest maupun User yang login).
// Langsung menampilkan halaman peta.
Route::get('/', function () {
    return view('map');
})->name('home');


// =========================================================
// 2. PENGALIHAN DASHBOARD (TRICK POST-LOGIN)
// =========================================================
// Bawaan Laravel Breeze akan melempar user ke '/dashboard' setelah login.
// Karena aplikasi ini tidak memakai dashboard, kita pantulkan otomatis 
// arahannya kembali ke '/' (halaman Map).
Route::redirect('/dashboard', '/')->name('dashboard');


// =========================================================
// 3. PENGATURAN PROFIL (PRIVATE)
// =========================================================
// Rute ini bawaan Laravel Breeze untuk mengubah nama, email, dan password.
// Wajib login untuk bisa mengakses rute ini.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memanggil rute-rute otentikasi bawaan Breeze (Login, Register, Logout, dll)
require __DIR__.'/auth.php';