<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\Resident;
use App\Models\User;
use App\Models\Setoran;
use Illuminate\Container\Attributes\Auth;

class PenarikanController extends Controller
{
    public function index()
    {
        $penarikans = Penarikan::with('petugas', 'resident', 'setoran')->get();
        return view('penarikan.index', compact('penarikans'));
    }

    public function create()
    {
        $residents = Resident::all();
        $petugas = User::where('role', 'petugas')->where('id', auth()->id())->first();
        // dd($petugas);
        return view('penarikan.create', compact('residents', 'petugas'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'petugas_id' => 'required|exists:users,id',
            'resident_id' => 'required|exists:residents,id',
            'amount' => 'required|numeric|min:0',
            'tanggal_penarikan' => 'required|date',
        ]);
        // dd($request->all());
        Penarikan::create($request->all());

        return redirect()->route('penarikan.index')->with('success', 'Data penarikan berhasil ditambahkan.');
    }

    public function show(Penarikan $penarikan)
    {
        return view('penarikan.show', compact('penarikan'));
    }

    public function edit(Penarikan $penarikan)
    {
        $residents = Resident::all();
        $petugas = User::where('role', 'petugas')->get();
        return view('penarikan.edit', compact('penarikan', 'residents', 'petugas'));
    }

    public function update(Request $request, Penarikan $penarikan)
    {
        $request->validate([
            'petugas_id' => 'required|exists:users,id',
            'resident_id' => 'required|exists:residents,id',
            'amount' => 'required|numeric|min:0',
            'tanggal_penarikan' => 'required|date',
        ]);

        $penarikan->update($request->all());

        return redirect()->route('penarikan.index')->with('success', 'Data penarikan berhasil diperbarui.');
    }

    public function destroy(Penarikan $penarikan)
    {
        $penarikan->delete();

        return redirect()->route('penarikan.index')->with('success', 'Data penarikan berhasil dihapus.');
    }
    public function getresidents(Request $request)
    {
        $resident_id = $request->input('resident_id'); // Mendapatkan parameter 'resident_id'

        // Jika ada 'resident_id', filter berdasarkan id tersebut, jika tidak, ambil semua data
        if ($resident_id) {
            $penarikans = Penarikan::with(['petugas', 'resident', 'setoran'])
                ->where('resident_id', $resident_id)
                ->get();
        } else {
            $penarikans = Penarikan::with(['petugas', 'resident', 'setoran'])->get();
        }
    
        return response()->json($penarikans); // Mengembalikan data dalam format JSON
    }
}
