<?php

namespace App\Http\Controllers\rt;

use App\Http\Controllers\Controller;
use App\Models\SaldoRt;
use App\Models\SaldoRtLog;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $saldo = SaldoRt::latest()->first(); // Ambil saldo terakhir
        $logSaldos = SaldoRtLog::latest()->paginate(10); // Ambil log saldo dengan paging 10 per halaman

        // Total Setoran dan Pengeluaran
        $totalSetoran = SaldoRtLog::where('jenis_transaksi', 'setoran')->sum('jumlah');
        $totalPengeluaran = SaldoRtLog::where('jenis_transaksi', 'pengeluaran')->sum('jumlah');
        // $totals = Penarikan::join('residents', 'penarikans.resident_id', '=', 'residents.id')
        //     ->selectRaw('residents.RT, SUM(penarikans.amount) as total_amount')
        //     ->groupBy('residents.RT')
        //     ->get();
        // dd($totals);
        return view('saldo.index', compact('saldo', 'logSaldos', 'totalSetoran', 'totalPengeluaran'));
    }
}
