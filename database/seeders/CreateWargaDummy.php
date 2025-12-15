<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CreateWargaDummy extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach (range(1, 100) as $i) {
            DB::table('warga')->insert([
                'no_ktp'        => $faker->unique()->numerify('################'),
                'nama'          => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'agama'         => $faker->randomElement([
                    'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'
                ]),
                'pekerjaan'     => $faker->jobTitle,
                'telp'          => $faker->numerify('08##########'),
                'email'         => $faker->unique()->safeEmail,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
