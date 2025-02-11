<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use Carbon\Carbon;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::latest()->paginate(10);
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        return view('residents.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'slug' => 'required|string|unique:residents,slug',  // Validasi slug harus unik
            'blok' => 'required|string|max:50',
            'nomor_rumah' => 'required|string|max:10',
            'RT' => 'required|string|max:5',
            'RW' => 'required|string|max:5',
            'address' => 'required|string',
        ]);

        // Menambahkan angka 0 di depan nomor rumah jika hanya 1 digit
        $nomorRumah = $request->input('nomor_rumah');
        if (strlen($nomorRumah) == 1) {
            $nomorRumah = '0' . $nomorRumah; // Menambahkan angka 0 jika hanya 1 digit
        }


        // Simpan data dengan slug yang sudah dibuat dan nomor rumah yang diformat
        Resident::create([
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number'),
            'slug' => $request->input('slug'), // Gabungan blok dan nomor rumah
            'blok' => strtoupper($request->input('blok')),  // Pastikan blok diubah ke kapital
            'nomor_rumah' => $nomorRumah,  // Nomor rumah yang sudah diformat
            'RT' => $request->input('RT'),
            'RW' => $request->input('RW'),
            'address' => $request->input('address'),
        ]);

        return redirect()->route('residents.index')->with('success', 'Data berhasil disimpan');
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
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'slug' => 'required|string|unique:residents,slug',  // Validasi slug harus unik
            'blok' => 'required|string|max:50',
            'nomor_rumah' => 'required|string|max:10',
            'RT' => 'required|string|max:5',
            'RW' => 'required|string|max:5',
            'address' => 'required|string',
        ]);

        // Menambahkan angka 0 di depan nomor rumah jika hanya 1 digit
        $nomorRumah = $request->input('nomor_rumah');
        if (strlen($nomorRumah) == 1) {
            $nomorRumah = '0' . $nomorRumah; // Menambahkan angka 0 jika hanya 1 digit
        }



        // Update data dengan slug yang sudah dibuat dan nomor rumah yang diformat
        $resident->update([
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number'),
            'slug' => $request->input('slug'),  // Gabungan blok dan nomor rumah
            'blok' => strtoupper($request->input('blok')),  // Pastikan blok diubah ke kapital
            'nomor_rumah' => $nomorRumah,  // Nomor rumah yang sudah diformat
            'RT' => $request->input('RT'),
            'RW' => $request->input('RW'),
            'address' => $request->input('address'),
        ]);

        // Redirect setelah berhasil update
        return redirect()->route('residents.index')->with('success', 'Resident berhasil diperbarui!');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return redirect()->route('residents.index')->with('success', 'Resident berhasil dihapus.');
    }
    public function totaAamount(Request $request)
    {
        $date = Carbon::parse('2025-01-01'); // Tanggal yang ingin dihitung
        $now = Carbon::now(); // Tanggal sekarang

        // Menghitung selisih hari
        $totalHari = $date->diffInDays($now);
        $formattedNumber = number_format($totalHari, 0, ',', '.');
        // dd($formattedNumber);
        $totalAmount = $formattedNumber * 1000;
        $residents = Resident::withSum('penarikan', 'amount')->get();

        return view('residents.amount', compact('residents', 'formattedNumber', 'totalAmount'));
    }
}
