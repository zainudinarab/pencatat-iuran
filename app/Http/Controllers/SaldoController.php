<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\LogSaldo;
use App\Models\Penarikan;
use App\Models\Resident;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:saldones-view')->only('index');
      
    }
    // Menampilkan saldo dan log saldo
    public function index()
    {
        $saldo = Saldo::latest()->first(); // Ambil saldo terakhir
        $logSaldos = LogSaldo::latest()->paginate(10); // Ambil log saldo dengan paging 10 per halaman

        // Total Setoran dan Pengeluaran
        $totalSetoran = LogSaldo::where('jenis_transaksi', 'setoran')->sum('jumlah');
        $totalPengeluaran = LogSaldo::where('jenis_transaksi', 'pengeluaran')->sum('jumlah');
        $totals = Penarikan::join('residents', 'penarikans.resident_id', '=', 'residents.id')
            ->selectRaw('residents.RT, SUM(penarikans.amount) as total_amount')
            ->groupBy('residents.RT')
            ->get();
        // dd($totals);
        return view('saldo.index', compact('saldo', 'logSaldos', 'totalSetoran', 'totalPengeluaran'));
    }

    // Fungsi untuk update saldo dan menambah log
    public function updateSaldo(Request $request)
    {
        $saldo = Saldo::latest()->first();
        $jumlah = $request->input('jumlah');
        $jenisTransaksi = $request->input('jenis_transaksi');

        // Update saldo
        if ($jenisTransaksi == 'setoran') {
            $saldo->saldo += $jumlah;
        } elseif ($jenisTransaksi == 'pengeluaran') {
            $saldo->saldo -= $jumlah;
        }
        $saldo->save();

        // Simpan log saldo
        LogSaldo::create([
            'user_id' => auth()->id(),
            'jenis_transaksi' => $jenisTransaksi,
            'jumlah' => $jumlah,
            'saldo_terakhir' => $saldo->saldo
        ]);

        return redirect()->route('saldo.index')->with('success', 'Saldo berhasil diperbarui!');
    }
}
