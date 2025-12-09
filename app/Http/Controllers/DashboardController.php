<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller
{
    public function index()
    {
        // Jika user BELUM login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika sudah login, tampilkan dashboard
        return view('admin.dashboard.index');
    }
}
