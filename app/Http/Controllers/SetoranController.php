<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setoran;
use App\Models\User;

class SetoranController extends Controller
{
    public function index()
    {
        $setorans = Setoran::with('bendahara')->get();
        return view('setoran.index', compact('setorans'));
    }

    public function create()
    {
        $bendaharas = User::all();
        return view('setoran.create', compact('bendaharas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bendahara_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'tanggal_setoran' => 'required|date',
            'status' => 'required|in:pending,confirmed',
        ]);

        Setoran::create($request->all());

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
}
