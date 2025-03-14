<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\Resident;
use App\Models\User;
use App\Models\Setoran;
use Illuminate\Support\Facades\Auth;
use App\Exports\PenarikanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class PenarikanController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:penarikans-view')->only('index', 'show');
        $this->middleware('permission:penarikans-create')->only('create', 'store');
        $this->middleware('permission:penarikans-edit')->only('edit', 'update');
        $this->middleware('permission:penarikans-delete')->only('destroy');
    }
    public function index()
    {

        $user = Auth::user();

        if ($user->role === 'bendahara') {
            $penarikans = Penarikan::with('petugas', 'resident', 'setoran')->get();
            $totals = Penarikan::selectRaw('
                        SUM(penarikans.amount) as total_amount,
                        SUM(CASE WHEN penarikans.setoran_id IS NOT NULL THEN penarikans.amount ELSE 0 END) as total_setor,
                        SUM(CASE WHEN penarikans.setoran_id IS NULL THEN penarikans.amount ELSE 0 END) as total_belum_setor
                        ')
                ->first(); // Mengambil satu hasil yang sudah dijumlahkan
        } else {
            $penarikans = Penarikan::with('petugas', 'resident', 'setoran')->where('petugas_id', $user->id)->get();
            $totals = Penarikan::selectRaw('
                        SUM(penarikans.amount) as total_amount,
                        SUM(CASE WHEN penarikans.setoran_id IS NOT NULL THEN penarikans.amount ELSE 0 END) as total_setor,
                        SUM(CASE WHEN penarikans.setoran_id IS NULL THEN penarikans.amount ELSE 0 END) as total_belum_setor
                        ')
                ->where('penarikans.petugas_id', $user->id) // Filter berdasarkan petugas_id
                ->first(); // Mengambil satu hasil yang sudah dijumlahkan
        }
        // $penarikans = Penarikan::with('petugas', 'resident', 'setoran')->get();
        return view('penarikan.index', compact('penarikans', 'totals'));
    }

    public function create()
    {
        $residents = Resident::all();
        $petugas = User::where('id', auth()->id())->first();

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

    public function edit($id)
    {
        $penarikan = Penarikan::findOrFail($id);
        // dd($penarikan);
        $residents = Resident::all();  // Jika perlu menampilkan daftar residents untuk pemilihan
        $petugas = User::all();        // Jika perlu menampilkan daftar petugas untuk pemilihan
        $setorans = Setoran::all();    // Jika perlu menampilkan daftar setoran untuk pemilihan

        return view('penarikan.edit', compact('penarikan', 'residents', 'petugas', 'setorans'));
    }

    public function update(Request $request, Penarikan $penarikan)
    {
        // dd($request->all());
        $request->validate([
            'petugas_id' => 'required|exists:users,id',
            'resident_id' => 'required|exists:residents,id',
            'amount_numeric' => 'required|numeric|min:0',
            'tanggal_penarikan' => 'required|date',
        ]);

        // Update the penarikan record
        $penarikan->update([
            'petugas_id' => $request->petugas_id,
            'resident_id' => $request->resident_id,
            'amount' => $request->amount_numeric,
            'tanggal_penarikan' => $request->tanggal_penarikan,
        ]);

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

    public function downloadExcel()
    {
        return Excel::download(new PenarikanExport, 'penarikans.xlsx');
    }
    public function downloadPDF()
    {
        $penarikans = Penarikan::with('petugas', 'resident', 'setoran')->get();
        $pdf = PDF::loadView('pdf.penarikan', compact('penarikans'));

        return $pdf->download('penarikans.pdf');
    }
    // indextarikan
    public function tarikan()
    {
        // Ambil semua data penarikan dengan relasi petugas, resident, dan setoran
        $penarikans = Penarikan::with('petugas', 'resident', 'setoran')->get();

        // Kelompokkan penarikan berdasarkan resident_id
        $groupedPenarikans = $penarikans->groupBy('resident_id');

        // Inisialisasi array untuk menyusun data untuk tabel
        $tableData = [];
        $no = 1; // Nomor urut

        // Loop untuk setiap kelompok berdasarkan resident_id
        foreach ($groupedPenarikans as $residentId => $penarikansGroup) {
            // Menyiapkan data untuk satu baris (untuk satu resident_id)
            $row = ['no' => $no, 'resident_id' => $residentId];
            // Loop untuk setiap penarikan dalam kelompok dan beri nama tarikan1, tarikan2, dll.
            $tarikanIndex = 1;
            foreach ($penarikansGroup as $penarikan) {
                // Tambahkan data tarikan dalam format tarikan1, tarikan2, dll.
                $row['tarikan' . $tarikanIndex] = $penarikan->amount;
                $tarikanIndex++;
            }

            // Tambahkan data row ke tableData
            $tableData[] = $row;
            $no++;
        }
        // dd($tableData);
        // Kirim data ke view
        return view('penarikan.tarikan', compact('tableData'));
    }
}
