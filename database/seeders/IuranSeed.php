<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisIuran;
use App\Models\IuranWajib;
use App\Models\RT;



class IuranSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create 'Iuran Wajib' in jenis_iurans table
        $jenisIuran = JenisIuran::create([
            'name' => 'Iuran Wajib'
        ]);

        // Assuming RT 11 already exists, if not, you can create it here as well
        $rt = RT::where('name', 'RT 11')->first(); // Replace with your RT retrieval logic if necessary

        // Loop through months January 2025 to December 2025
        for ($month = 1; $month <= 12; $month++) {
            // Format the month as YYYYMM
            $billMonth = 202500 + $month;

            // Insert the iuran wajib record for each month
            IuranWajib::create([
                'rt_id' => $rt->id,
                'jenis_iuran_id' => $jenisIuran->id,
                'bill_month' => $billMonth,  // Format YYYYMM
                'amount' => 30000.00, // Set the iuran amount here (e.g., 100000.00)
            ]);
        }
    }
}
