<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\RT;
use App\Models\Gang;
use App\Models\House;
use App\Models\User;
use Faker\Factory as Faker;

class RTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Create RT 11
        $rt = RT::create([
            'name' => 'RT 11',
            'ketua_rt_id' => 1, // Replace with actual user ID for Ketua RT
            'bendahara_id' => 2, // Replace with actual user ID for Bendahara
            'rw' => 'RW 3', // Example RW, replace if needed
            'village' => 'Desa ABC', // Example village name
            'district' => 'Kecamatan XYZ', // Example district name
            'city' => 'Kota Jakarta', // Example city
        ]);

        // Create Gang 7
        $gang = Gang::create([
            'rt_id' => $rt->id,
            'ketua_gang_id' => 3, // Replace with actual user ID for Ketua Gang
            'name' => 'Gang 7',
        ]);

        // Create Houses L01 to L10
        for ($i = 1; $i <= 10; $i++) {
            House::create([
                'id' => 'L' . str_pad($i, 2, '0', STR_PAD_LEFT), // L01 to L10
                'blok' => 'L', // Block L
                'nomer' => $i, // House number 1 to 10
                'rt_id' => $rt->id,
                'gang_id' => $gang->id,
                'name' => $faker->name, // Generate fake name for house owner
                'address' => 'Alamat Rumah L' . str_pad($i, 2, '0', STR_PAD_LEFT), // Example address
            ]);
        }
    }
}
