<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\ProgramBantuanController;
use App\Http\Controllers\Admin\PendaftarBantuanController;
use App\Http\Controllers\Admin\VerifikasiLapanganController;
use App\Http\Controllers\Admin\PenerimaBantuanController;
use App\Http\Controllers\Admin\RiwayatPenyaluranBantuanController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProfileController;

// ==========================
// LOGIN
// ==========================
Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ==========================
// ADMIN ROUTES
// ==========================
Route::group([
    'prefix' => 'admin',
    'middleware' => ['checkislogin', 'checkrole:Admin']
], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('users', UsersController::class);
    Route::resource('warga', WargaController::class);
    Route::resource('program_bantuan', ProgramBantuanController::class);
    Route::resource('pendaftar_bantuan', PendaftarBantuanController::class);
    Route::resource('verifikasi_lapangan', VerifikasiLapanganController::class);
    Route::resource('penerima_bantuan', PenerimaBantuanController::class);
    Route::resource('riwayat_penyaluran_bantuan', RiwayatPenyaluranBantuanController::class);
    Route::resource('media', MediaController::class);

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'show'])->name('admin.profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('auth.logout');
