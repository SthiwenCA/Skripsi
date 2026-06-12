<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoadDamageSubmissionController;
use App\Http\Controllers\AdminController; 
use Illuminate\Support\Facades\Route;

// =========================================================
// 1. HALAMAN UTAMA (PUBLIC)
// =========================================================
// Halaman Peta Interaktif (Sekarang menjadi halaman depan /)
Route::get('/', function () {
    return view('map');
})->name('map');

// Halaman Informasi & Panduan
Route::get('/information', function () {
    return view('landing');
})->name('information');

// Trick Post-Login Bypass
Route::redirect('/dashboard', '/')->name('dashboard');

// =========================================================
// 2. FITUR KHUSUS USER & ADMIN (HANYA BUTUH LOGIN)
// =========================================================
Route::middleware('auth')->group(function () {
    
    // --- PENGATURAN PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- LIST LAPORAN / HISTORY USER ---
    Route::get('/my-reports', [RoadDamageSubmissionController::class, 'history'])->name('user.history');

    // --- FORM PELAPORAN KERUSAKAN JALAN ---
    Route::get('/submissions/create', [RoadDamageSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [RoadDamageSubmissionController::class, 'store'])->name('submissions.store');
    
    // Rute untuk mengirim foto bukti jalan sudah mulus / diperbaiki
    Route::post('/submissions/{id}/road-fixed', [RoadDamageSubmissionController::class, 'submitRoadFixed'])->name('submissions.road-fixed');
});

// =========================================================
// 3. FITUR KHUSUS ADMIN (TABEL LIST VERIFIKASI & MAP ACTION)
// =========================================================
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // --- ROUTE PERUBAHAN STATUS (STATE MACHINE) ---
    Route::patch('/admin/report/{id}/approve', [AdminController::class, 'approve'])->name('admin.report.approve');
    Route::patch('/admin/report/{id}/approve-fixed', [AdminController::class, 'approveFixed'])->name('admin.report.approve-fixed');
    Route::patch('/admin/report/{id}/reject', [AdminController::class, 'reject'])->name('admin.report.reject');
    
    // --- ROUTE HAPUS PERMANEN (DELETE DISASTER) ---
    Route::delete('/admin/report/{id}', [AdminController::class, 'destroy'])->name('admin.report.destroy');
});

// =========================================================
// 4. HALAMAN SUKSES VERIFIKASI (DIUBAH MENJADI REDIRECT KE MAP)
// =========================================================
// Menghindari error view [auth.verified] not found dengan me-redirect ke map beserta pesan sukses (Toast alert)
Route::get('/email/verified', function () {
    return redirect()->route('map')->with('success', 'Email Anda berhasil diverifikasi! Selamat datang di Map Kerusakan Jalan.');
})->middleware(['auth', 'verified'])->name('auth.verified');

// Memanggil rute-rute otentikasi bawaan Breeze
require __DIR__.'/auth.php';