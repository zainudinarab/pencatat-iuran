<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use Carbon\Carbon;

use App\Models\Penarikan;

class ResidentController extends Controller
{
    function __construct()
    {
       // Permissions for the 'residents' table
       $this->middleware('permission:residents-view')->only('index', 'show');
       $this->middleware('permission:residents-create')->only('create', 'store');
       $this->middleware('permission:residents-edit')->only('edit', 'update');
       $this->middleware('permission:residents-delete')->only('destroy');
    }
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
            'id' => 'required|string|unique:residents,id',  // Validasi slug harus unik
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'blok' => 'required|string|max:50',
            'nomor_rumah' => 'required|string|max:10',
            'RT' => 'required|string|max:5',
            'RW' => 'required|string|max:5',

        ]);

        // Menambahkan angka 0 di depan nomor rumah jika hanya 1 digit
        $nomorRumah = $request->input('nomor_rumah');
        if (strlen($nomorRumah) == 1) {
            $nomorRumah = '0' . $nomorRumah; // Menambahkan angka 0 jika hanya 1 digit
        }


        // Simpan data dengan slug yang sudah dibuat dan nomor rumah yang diformat
        Resident::create([
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number'),
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
            'phone_number' => 'nullable|string|max:20',
            'RT' => 'required|string|max:5',
            'RW' => 'required|string|max:5',

        ]);

        // Update data dengan slug yang sudah dibuat dan nomor rumah yang diformat
        $resident->update([
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number') ?? $resident->phone_number,
            'RT' => $request->input('RT'),
            'RW' => $request->input('RW'),
            'address' => $request->input('address') ?? $resident->address,
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
    // detail
    public function detail(Resident $resident)

    {

        $resident_id = $resident->id;
        $penarikans = Penarikan::with(['petugas', 'resident', 'setoran'])
            ->where('resident_id', $resident_id)
            ->get();
        // dd($penarikans);
        return view('residents.detail', compact('penarikans'));
    }
}
