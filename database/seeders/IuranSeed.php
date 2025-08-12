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
            'name' => 'Iuran Wajib',
            'amount' => 30000.00
        ]);

        // Ensure RT 11 exists
        $rt = RT::firstOrCreate(['name' => 'RT 11']);

        // Loop through months January 2025 to December 2025
        for ($month = 1; $month <= 12; $month++) {
            $billMonth = (int)('2025' . str_pad($month, 2, '0', STR_PAD_LEFT));

            IuranWajib::create([
                'rt_id' => $rt->id,
                'jenis_iuran_id' => $jenisIuran->id,
                'bill_month' => $billMonth,  // Format YYYYMM
                'amount' => $jenisIuran->amount,
            ]);
        }
    }
}
