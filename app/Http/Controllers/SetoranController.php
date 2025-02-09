<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setoran;
use App\Models\User;
use App\Models\Penarikan;

class SetoranController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'bendahara') {
            $setorans = Setoran::with('bendahara')->get();
        } else {
            $setorans = Setoran::with('bendahara')->where('bendahara_id', $user->id)->get();
        }
        // $setorans = Setoran::with('bendahara')->get();
        return view('setoran.index', compact('setorans'));
    }

    public function create()
    {
        $bendaharas = User::all();
        // by user login
        $petugas = User::where('role', 'petugas')->where('id', auth()->id())->first();
        // penarikan  by petugas_id
        $penarikan = Penarikan::where('petugas_id', $petugas->id)
            ->with('resident') // Eager load the 'resident' relationship
            ->get();
        // dd($penarikan->resident());

        return view('setoran.create', compact('bendaharas', 'petugas', 'penarikan'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'petugas_id' => 'required|exists:users,id',
            'tanggal_setoran' => 'required|date',
            'penarikan_ids' => 'required|array',
            'penarikan_ids.*' => 'exists:penarikans,id',
            'total_amount' => 'required|numeric',
        ]);

        // Create a new setoran record
        $setoran = Setoran::create([
            'bendahara_id' => $request->petugas_id,
            'total_amount' => $request->total_amount,
            'tanggal_setoran' => $request->tanggal_setoran,
            'status' => 'pending',  // You can modify this as needed
        ]);

        // Update the related penarikans to associate them with the new setoran_id
        Penarikan::whereIn('id', $request->penarikan_ids)
            ->update(['setoran_id' => $setoran->id]);


        return redirect()->route('setoran.index')->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function show(Setoran $setoran)
    {
        return view('setoran.show', compact('setoran'));
    }

    public function edit(Setoran $setoran)
    {
        $bendaharas = User::all();
        return view('setoran.edit', compact('setoran', 'bendaharas'));
    }

    public function update(Request $request, Setoran $setoran)
    {
        $request->validate([
            'bendahara_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'tanggal_setoran' => 'required|date',
            'status' => 'required|in:pending,confirmed',
        ]);

        $setoran->update($request->all());

        return redirect()->route('setoran.index')->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy(Setoran $setoran)
    {
        $setoran->delete();

        return redirect()->route('setoran.index')->with('success', 'Setoran berhasil dihapus.');
    }

    public function confirmSetoran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $setoran = Setoran::findOrFail($id);
        $bendahara = auth()->user();

        // Buat konfirmasi setoran
        $konfirmasi = KonfirmasiSetoran::create([
            'setoran_id' => $setoran->id,
            'bendahara_id' => $bendahara->id,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        // Update status setoran
        if ($request->status == 'diterima') {
            $setoran->update(['status' => 'dikonfirmasi']);
        }

        return redirect()->route('setoran.index')->with('success', 'Setoran berhasil dikonfirmasi.');
    }
}
