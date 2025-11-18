<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PendaftarBantuanController;

Route::get('/', function () {
    return redirect()->route('program_bantuan.index');
});
Route::resource('program_bantuan', ProgramBantuanController::class);

Route::resource('warga', WargaController::class);

Route::resource('pendaftaran-bantuan', PendaftarBantuanController::class)->names('pendaftar-bantuan');
Route::get('pendaftar-bantuan/{any?}', function ($any = null) {
    $suffix = $any ? '/' . ltrim($any, '/') : '';
    return redirect('pendaftaran-bantuan' . $suffix);
})->where('any', '.*');

