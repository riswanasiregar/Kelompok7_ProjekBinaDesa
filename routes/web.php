<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PendaftarBantuanController;
use App\Http\Controllers\MultipleuploadsController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return redirect()->route('program_bantuan.index');
});
Route::resource('program_bantuan', ProgramBantuanController::class);

Route::resource('warga', WargaController::class);

Route::resource('pendaftar-bantuan', PendaftarBantuanController::class);
Route::resource('customers', CustomerController::class);

Route::get('/multipleuploads', [MultipleuploadsController::class, 'index'])->name('uploads');
Route::post('/save', [MultipleuploadsController::class, 'store'])->name('uploads.store');
Route::delete('/uploads/{multipleupload}', [MultipleuploadsController::class, 'destroy'])->name('uploads.destroy');
