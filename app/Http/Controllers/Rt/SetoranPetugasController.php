<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\SetoranPetugas;
use App\Models\User;
use App\Models\Rt;
use App\Models\SaldoRt;
use App\Models\SaldoLog;
use App\Models\Pembayaran;
use Illuminate\Http\Request;


class SetoranPetugasController extends Controller
{
    public function index()
    {
        $setoran = SetoranPetugas::with('collector', 'rt', 'approvedBy')->paginate(10);
        return view('rt.manage_setoran.index', compact('setoran'));
    }

    public function create()
    {
        $bendaharas = User::all();
        // by user login
        $petugas = User::where('id', auth()->id())->first();
        // penarikan  by petugas_id
        $pembayarans = Pembayaran::where('collector_id', $petugas->id)
            ->whereNull('setoran_id') // Add condition where 'setoran_id' is null
            ->with('collector','house') // Eager load the 'collector' relationship
            ->get();

        // dd($pembayarans);

      
        return view('rt.manage_setoran.create', compact('petugas', 'pembayarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'rt_id' => 'required|exists:rts,id',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,confirmed',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        // Menyimpan Setoran Petugas
        $setoran = SetoranPetugas::create($request->all());
        $saldo = SaldoRt::updateSaldo($setoran->rt_id, $setoran->total_amount, 'income');

        // Catat log transaksi saldo
        SaldoLog::createLog($setoran->rt_id, $setoran->total_amount, 'income', 'Setoran petugas', $setoran->id, 'SetoranPetugas');

        return redirect()->route('manage-rt.setoran.index')->with('success', 'Setoran petugas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $setoran = SetoranPetugas::findOrFail($id);
        $users = User::all();
        $rts = Rt::all();
        return view('rt.manage_setoran.form', compact('setoran', 'users', 'rts'));
    }

    public function update(Request $request, $id)
    {
        $setoran = SetoranPetugas::findOrFail($id);

        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'rt_id' => 'required|exists:rts,id',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,confirmed',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        $setoran->update($request->all());

        return redirect()->route('manage-rt.setoran.index')->with('success', 'Setoran petugas berhasil diperbarui');
    }

    public function destroy($id)
    {
        SetoranPetugas::destroy($id);
        return redirect()->route('manage-rt.setoran.index')->with('success', 'Setoran petugas berhasil dihapus');
    }
}
