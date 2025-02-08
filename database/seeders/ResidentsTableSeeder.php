<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class ResidentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Loop untuk menambahkan data ke tabel
        for ($i = 0; $i < 10; $i++) { // misalnya 10 data
            $blok = $faker->randomLetter(); // Contoh blok (misal A, B, C)
            $nomorRumah = $faker->numberBetween(1, 99); // Nomor rumah antara 1-99

            DB::table('residents')->insert([
                'name' => $faker->name,
                'phone_number' => $faker->phoneNumber,
                'slug' => strtoupper($blok) . str_pad($nomorRumah, 2, '0', STR_PAD_LEFT), // Gabungan blok dan nomor rumah
                'blok' => strtoupper($blok),
                'nomor_rumah' => str_pad($nomorRumah, 2, '0', STR_PAD_LEFT), // Format nomor rumah, misalnya "05"
                'RT' => $faker->numberBetween(1, 10),
                'RW' => $faker->numberBetween(1, 10),
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
