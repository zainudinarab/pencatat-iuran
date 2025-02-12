<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenarikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['resident_id' => 'I08', 'amount' => 2000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I08', 'amount' => 18000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I20', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I19', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I11', 'amount' => 1000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I11', 'amount' => 100000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I17', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I16', 'amount' => 20000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I15', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'I12', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'J07', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'J03', 'amount' => 100000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'J06', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'J12', 'amount' => 10000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'J17', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'K04', 'amount' => 50000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'L03', 'amount' => 20000.00, 'tanggal_penarikan' => '2025-01-02', 'petugas_id' => 4],
            ['resident_id' => 'K16', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-03', 'petugas_id' => 4],
            ['resident_id' => 'K14', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-03', 'petugas_id' => 4],
            ['resident_id' => 'L07', 'amount' => 210000.00, 'tanggal_penarikan' => '2025-01-03', 'petugas_id' => 4],
            ['resident_id' => 'K17', 'amount' => 10000.00, 'tanggal_penarikan' => '2025-01-03', 'petugas_id' => 4],
            ['resident_id' => 'J09', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-03', 'petugas_id' => 4],
            ['resident_id' => 'I06', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-05', 'petugas_id' => 4],
            ['resident_id' => 'L04', 'amount' => 50000.00, 'tanggal_penarikan' => '2025-01-05', 'petugas_id' => 4],
            ['resident_id' => 'L01', 'amount' => 210000.00, 'tanggal_penarikan' => '2025-01-08', 'petugas_id' => 4],
            ['resident_id' => 'K15', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-09', 'petugas_id' => 4],
            ['resident_id' => 'K11', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-24', 'petugas_id' => 4],
            ['resident_id' => 'K17', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-24', 'petugas_id' => 4],
            ['resident_id' => 'H10', 'amount' => 15000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'H14', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'H15', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I07', 'amount' => 10000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'H16', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I14', 'amount' => 50000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'J08', 'amount' => 7000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'K08', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'K07', 'amount' => 5000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'J13', 'amount' => 31000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'L06', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'L05', 'amount' => 50000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I02', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I10', 'amount' => 10000.00, 'tanggal_penarikan' => '2025-01-01', 'petugas_id' => 4],
            ['resident_id' => 'I09', 'amount' => 100000.00, 'tanggal_penarikan' => '2025-01-29', 'petugas_id' => 4],
            ['resident_id' => 'J12', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-30', 'petugas_id' => 4],
            ['resident_id' => 'I08', 'amount' => 10000.00, 'tanggal_penarikan' => '2025-01-30', 'petugas_id' => 4],
            ['resident_id' => 'J08', 'amount' => 23000.00, 'tanggal_penarikan' => '2025-01-30', 'petugas_id' => 4],
            ['resident_id' => 'L03', 'amount' => 20000.00, 'tanggal_penarikan' => '2025-01-31', 'petugas_id' => 4],
            ['resident_id' => 'I10', 'amount' => 20000.00, 'tanggal_penarikan' => '2025-01-31', 'petugas_id' => 4],
            ['resident_id' => 'I17', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-03', 'petugas_id' => 4],
            ['resident_id' => 'H16', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I06', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'H14', 'amount' => 10000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'K08', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'J17', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'K07', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'K07', 'amount' => 25000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'J13', 'amount' => 25000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I07', 'amount' => 20000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I12', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'J06', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I16', 'amount' => 40000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I20', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I19', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'H15', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'L09', 'amount' => 50000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'K16', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I15', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'I13', 'amount' => 50000.00, 'tanggal_penarikan' => '2025-02-10', 'petugas_id' => 4],
            ['resident_id' => 'K14', 'amount' => 60000.00, 'tanggal_penarikan' => '2025-02-11', 'petugas_id' => 4],
            ['resident_id' => 'J05', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-01-31', 'petugas_id' => 4],
            ['resident_id' => 'I08', 'amount' => 30000.00, 'tanggal_penarikan' => '2025-02-11', 'petugas_id' => 4],
        ];

        // Insert data ke tabel penarikans
        DB::table('penarikans')->insert($data);
    }
}
