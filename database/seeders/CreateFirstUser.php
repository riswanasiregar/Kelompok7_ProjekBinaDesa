<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateFirstUser extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'riswana24si@mahasiswa.pcr.ac.id',
            'password' => Hash::make('riswana123'),
            'role' => 'admin',
        ]);
    }
}
