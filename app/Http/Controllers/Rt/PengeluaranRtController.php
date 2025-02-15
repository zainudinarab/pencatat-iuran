<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\PengeluaranRt;
use App\Models\SaldoRt;
use App\Models\SaldoLog;

use App\Models\Rt;
use App\Models\User;
use Illuminate\Http\Request;

class PengeluaranRtController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranRt::with(['rt', 'approvedBy'])->paginate(10);
        return view('Rt.pengeluaran-rt.index', compact('pengeluaran'));
    }
    public function create()
    {
        $rts = Rt::all();
        $bendaharas = User::whereHas('roles', function ($query) {
            $query->where('name', 'bendahara');
        })->get();
        return view('Rt.pengeluaran-rt.create', compact('rts', 'bendaharas'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'approved_by' => 'required|exists:users,id',
        ]);

        // Menyimpan Pengeluaran RT
        $pengeluaran = PengeluaranRt::create($request->all());

        // Update saldo RT dengan mengurangi pengeluaran
        $saldo = SaldoRt::updateSaldo($pengeluaran->rt_id, $pengeluaran->amount, 'expense');

        // Catat log transaksi saldo
        SaldoLog::createLog($pengeluaran->rt_id, $pengeluaran->amount, 'expense', 'Pengeluaran RT', $pengeluaran->id, 'PengeluaranRt');

        return redirect()->route('manage-rt.pengeluaran-rt.index')->with('success', 'Pengeluaran RT berhasil disimpan');
    }
    public function edit(PengeluaranRt $pengeluaranRt)
    {
        $rts = Rt::all();
        $bendahara = User::whereHas('roles', function ($query) {
            $query->where('name', 'bendahara');
        })->get();
        return view('Rt.pengeluaran.edit', compact('pengeluaranRt', 'rts', 'bendahara'));
    }

    public function update(Request $request, PengeluaranRt $pengeluaranRt)
    {
        $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'approved_by' => 'required|exists:users,id',
        ]);

        $pengeluaranRt->update($request->all());

        return redirect()->route('manage-rt.pengeluaran.index')->with('success', 'Pengeluaran RT berhasil diperbarui.');
    }

    public function destroy(PengeluaranRt $pengeluaranRt)
    {
        $pengeluaranRt->delete();

        return redirect()->route('manage-rt.pengeluaran.index')->with('success', 'Pengeluaran RT berhasil dihapus.');
    }
}
