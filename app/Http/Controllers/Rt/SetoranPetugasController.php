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
use Illuminate\Support\Facades\Auth;


class SetoranPetugasController extends Controller
{
    public function index()
    {
        $setoran = SetoranPetugas::with('collector', 'rt', 'approvedBy')->paginate(10);
        return view('rt.manage_setoran.index', compact('setoran'));
    }

    public function create()
    {
        $user = User::with('rtKetua', 'rtBendahara', 'gang')->find(Auth::id());
        $roleData = $user->roleWithRT();
        // penarikan  by petugas_id
        $pembayarans = Pembayaran::where('collector_id', $user->id)
            ->whereNull('setoran_id') // Add condition where 'setoran_id' is null
            ->with('collector', 'house') // Eager load the 'collector' relationship
            ->get();

        // dd($pembayarans);


        return view('rt.manage_setoran.create', compact('user', 'roleData', 'pembayarans'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'rt_id' => 'required|exists:rts,id',
            'pembayaran_ids' => 'required|array',
            'total_amount' => 'required|numeric',
        ]);

        $setoran = SetoranPetugas::create([
            'collector_id' => $request->collector_id,
            'rt_id' => $request->rt_id,
            'total_amount' => $request->total_amount,
            'status' => 'pending', // Status setoran baru adalah 'pending'
            'approved_by' => null, // Belum disetujui
        ]);

        Pembayaran::whereIn('id', $request->pembayaran_ids)
            ->update(['setoran_id' => $setoran->id]);


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
