<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PendaftarBantuanController;
use App\Http\Controllers\MultipleuploadsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VerifikasiLapanganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware('checkislogin')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('program_bantuan.index');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('program_bantuan.index');
    })->name('home');

    Route::resource('program_bantuan', ProgramBantuanController::class);
    Route::resource('warga', WargaController::class);
    Route::resource('pendaftar-bantuan', PendaftarBantuanController::class);
    Route::resource('customers', CustomerController::class);

    Route::get('/multipleuploads', [MultipleuploadsController::class, 'index'])->name('uploads');
    Route::post('/save', [MultipleuploadsController::class, 'store'])->name('uploads.store');
    Route::delete('/uploads/{multipleupload}', [MultipleuploadsController::class, 'destroy'])->name('uploads.destroy');

 
 

    //  pengguna khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserManagementController::class)->except(['show']);
        Route::resource('verifikasi', VerifikasiLapanganController::class)->except(['show']);
    });
});

