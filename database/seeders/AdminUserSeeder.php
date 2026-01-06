<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek dulu apakah email admin sudah ada
        $email = 'admin@mahasiswa.pcr.ac.id';

        if (!User::where('email', $email)->exists()) {
            User::create([
                'name'     => 'Admin Utama',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('password123'), // ganti sesuai password yang aman
                'role'     => 'admin', // pastikan role sama dengan yang dicek di middleware
            ]);
        }
    }
}
