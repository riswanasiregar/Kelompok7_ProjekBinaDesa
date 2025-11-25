<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\PendaftarBantuanController;
use App\Http\Controllers\UsersController;

//dashboard
Route::get('/', function () {
    return view('dashboard');
});
//layout without menu
Route::get('/layout-without-menu', function () {
    return view('layout-without-menu');
})->name('layout.without.menu');
//login
Route::get('/login', function () {
    return view('login');
})->name('login');
//layout without navbar
Route::get('/layout-without-navbar', function () {
    return view('layout-without-navbar');
})->name('layout.without.navbar');
//Route Warga
Route::resource('warga', WargaController::class);
//Route Program Bantuan
Route::resource('program_bantuan', ProgramBantuanController::class);
//Route Pendaftar Bantuan
Route::resource('pendaftar_bantuan', PendaftarBantuanController::class);
//Route Users
Route::resource('users', UsersController::class);
//Route Media
Route::resource('media', MediaController::class);
//Route Verifikasi lapangan
Route::resource('verifikasi_lapangan', VerifikasiLapanganController::class);
//Route penerima bantuan
Route::resource('penerima_bantuan', PenerimaBantuanController::class);
//Route Riwayat penyaluran bantuan
Route::resource('riwayat_penyaluran_bantuan', RiwayatPenyaluranBantuan::class);


