<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\PendaftarBantuanController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VerifikasiLapanganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenerimaBantuanController;
use App\Http\Controllers\RiwayatPenyaluranBantuanController;
use App\Http\Middleware\CheckIsLogin;

//Route tanpa Middleware
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

//layout without menu
Route::get('/layout-without-menu', function () {
    return view('layout-without-menu');
})->name('layout.without.menu');

//layout without navbar
Route::get('/layout-without-navbar', function () {
    return view('layout-without-navbar');
})->name('layout.without.navbar');

//Route dengan CheckIsLogin
Route::group(['middleware' => ['checkislogin']], function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('warga', WargaController::class);
    Route::resource('program_bantuan', ProgramBantuanController::class);
    Route::resource('pendaftar_bantuan', PendaftarBantuanController::class);
    Route::resource('users', UsersController::class);
    Route::resource('media', MediaController::class);
    Route::resource('verifikasi_lapangan', VerifikasiLapanganController::class);
    Route::resource('penerima_bantuan', PenerimaBantuanController::class);
    Route::resource('riwayat_penyaluran_bantuan', RiwayatPenyaluranBantuanController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Media delete route
    Route::delete('/program_bantuan/{programId}/media/{mediaId}',
        [ProgramBantuanController::class, 'deleteMedia']
    )->name('program_bantuan.deleteMedia');

    // Update media order
    Route::post('program_bantuan/update-media-order',
        [ProgramBantuanController::class, 'updateMediaOrder']
    )->name('program_bantuan.updateMediaOrder');
});

  Route::group(['middleware' => ['checkrole:Admin']], function () {
        // Route yang hanya dapat diakses oleh admin
        Route::resource('users', UsersController::class);
    });
