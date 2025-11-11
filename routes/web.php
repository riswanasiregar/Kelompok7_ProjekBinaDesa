<?php

use Illuminate\Support\Facades\Route;
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


