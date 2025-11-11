<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\PendaftarBantuanController;
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


