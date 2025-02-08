<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::all();
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        return view('residents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'blok' => 'required|string|max:10',
            'nomor_rumah' => 'required|string|max:10',
            'address' => 'required|string',
        ]);

        Resident::create($request->all());

        return redirect()->route('residents.index')->with('success', 'Resident berhasil ditambahkan.');
    }

    public function show(Resident $resident)
    {
        return view('residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        return view('residents.edit', compact('resident'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'blok' => 'required|string|max:10',
            'nomor_rumah' => 'required|string|max:10',
            'address' => 'required|string',
        ]);

        $resident->update($request->all());

        return redirect()->route('residents.index')->with('success', 'Resident berhasil diperbarui.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return redirect()->route('residents.index')->with('success', 'Resident berhasil dihapus.');
    }
}
