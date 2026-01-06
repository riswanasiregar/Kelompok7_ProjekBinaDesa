<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\WargaController as AdminWargaController;
use App\Http\Controllers\Admin\ProgramBantuanController as AdminProgramBantuanController;
use App\Http\Controllers\Admin\PendaftarBantuanController as AdminPendaftarBantuanController;
use App\Http\Controllers\Admin\VerifikasiLapanganController as AdminVerifikasiLapanganController;
use App\Http\Controllers\Admin\PenerimaBantuanController as AdminPenerimaBantuanController;
use App\Http\Controllers\Admin\RiwayatPenyaluranBantuanController as AdminRiwayatPenyaluranBantuanController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProfileController;

// GUEST / UMUM CONTROLLERS
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PendaftarBantuanController;
use App\Http\Controllers\MultipleuploadsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VerifikasiLapanganController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PenerimaBantuanController;
use App\Http\Controllers\RiwayatPenyaluranBantuanController;

// ==========================
// LOGIN & REGISTER
// ==========================
Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    // Pakai nama route "login" supaya sesuai dengan AuthController::logout() dan middleware lain
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// ==========================
// GUEST / USER BIASA ROUTES
// ==========================
Route::middleware('auth')->group(function () {
    // Dashboard untuk guest / user biasa
    Route::get('/dashboard', function () {
        return redirect()->route('program_bantuan.index');
    })->name('dashboard');

    Route::resource('program_bantuan', ProgramBantuanController::class);
    Route::resource('warga', WargaController::class);
    Route::resource('pendaftar-bantuan', PendaftarBantuanController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('penerima', PenerimaBantuanController::class);
    Route::resource('riwayat', RiwayatPenyaluranBantuanController::class);
    Route::resource('verifikasi', VerifikasiLapanganController::class)->except(['show']);

    Route::resource('users', UserManagementController::class);

    Route::get('/multipleuploads', [MultipleuploadsController::class, 'index'])->name('uploads');
    Route::post('/save', [MultipleuploadsController::class, 'store'])->name('uploads.store');
    Route::delete('/uploads/{multipleupload}', [MultipleuploadsController::class, 'destroy'])->name('uploads.destroy');
});

// Redirect jalur lama ke yang baru (jika ada yang masih mengakses /penerima_bantuan)
Route::redirect('/penerima_bantuan', '/penerima');

// ==========================
// ADMIN ROUTES
// ==========================
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['checkislogin', 'checkrole:admin']
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard'); // Sekarang route name = admin.dashboard

    Route::resource('users', UsersController::class);
    Route::resource('warga', AdminWargaController::class);
    Route::resource('program_bantuan', AdminProgramBantuanController::class);
    Route::resource('pendaftar_bantuan', AdminPendaftarBantuanController::class);
    Route::resource('verifikasi_lapangan', AdminVerifikasiLapanganController::class);
    Route::resource('penerima_bantuan', AdminPenerimaBantuanController::class);
    Route::resource('riwayat_penyaluran_bantuan', AdminRiwayatPenyaluranBantuanController::class);
    Route::resource('media', MediaController::class);

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
