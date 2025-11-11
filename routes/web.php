<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramBantuanController;

Route::get('/', function () {
    return redirect()->route('program_bantuan.index');
});
Route::resource('program_bantuan', ProgramBantuanController::class);

