<?php

namespace App\Http\Controllers;

use App\Models\Penarikan;
use App\Models\Resident;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan total amount berdasarkan RT.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mengambil data total amount berdasarkan RT
        // $totals = Penarikan::join('residents', 'penarikans.resident_id', '=', 'residents.id')
        //     ->selectRaw('residents.RT, SUM(penarikans.amount) as total_amount')
        //     ->groupBy('residents.RT')
        //     ->get();
        $totals = Penarikan::join('residents', 'penarikans.resident_id', '=', 'residents.id')
            ->selectRaw('
            residents.RT,
            SUM(penarikans.amount) as total_amount,
            SUM(CASE WHEN penarikans.setoran_id IS NOT NULL THEN penarikans.amount ELSE 0 END) as total_setor,
            SUM(CASE WHEN penarikans.setoran_id IS NULL THEN penarikans.amount ELSE 0 END) as total_belum_setor
        ')
            ->groupBy('residents.RT')
            ->get();
        // Mengembalikan tampilan dengan data totals
        return view('laporan.index', compact('totals'));
    }
}
