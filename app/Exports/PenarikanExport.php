<?php

namespace App\Exports;

use App\Models\Penarikan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class PenarikanExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
     * Mengambil semua data dari model Penarikan.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Penarikan::with('petugas', 'resident', 'setoran') // Menyertakan relasi
            ->get()
            ->map(function ($penarikan) {
                return [
                    $penarikan->id,
                    $penarikan->petugas->name ?? 'N/A',  // Asumsi nama petugas ada di field 'name' pada tabel users
                    $penarikan->resident->name ?? 'N/A',  // Asumsi nama resident ada di field 'name' pada tabel residents
                    $penarikan->amount,

                    $penarikan->tanggal_penarikan = Carbon::parse($penarikan->tanggal_penarikan), // Mengonversi menjadi Carbon
                    $penarikan->setoran->amount ?? 'N/A',  // Asumsi ada field 'amount' pada tabel setoran
                ];
            });
    }

    /**
     * Menambahkan judul kolom.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Penarikan',
            'Nama Petugas',
            'Nama Resident',
            'Jumlah Penarikan',
            'Tanggal Penarikan',
            'Jumlah Setoran',
        ];
    }

    /**
     * Format kolom tertentu
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_00,  // Format jumlah penarikan sebagai angka dengan dua desimal
            'F' => NumberFormat::FORMAT_NUMBER_00,  // Format jumlah setoran sebagai angka dengan dua desimal
        ];
    }
}
