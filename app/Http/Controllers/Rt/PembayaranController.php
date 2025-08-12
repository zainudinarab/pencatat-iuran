<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\House;
use App\Models\User;
use App\Models\IuranWajib;
use App\Models\DetailPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PembayaranController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $this->authorize('viewAny', Pembayaran::class);

    $query = Pembayaran::with(['house', 'collector', 'setoran', 'detailPembayaran.iuranWajib']);

    // Jika user memiliki role ketua_rt atau bendahara_rt, filter berdasarkan id mereka
    if ($user->hasAnyRole(['ketua_rt', 'bendahara_rt'])) {
        $query->where('collector_id', $user->id);
    }

    $pembayaran = $query->paginate(10);
    
    return view('rt.manage_pembayaran.index', compact('pembayaran'));
}
     public function pembayaranGlobal()
    {
        $this->authorize('viewAny', Pembayaran::class);

        $pembayaran = Pembayaran::with(['house', 'collector', 'setoran', 'detailPembayaran.iuranWajib'])->paginate(10);
        return view('rt.manage_pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $this->authorize('create', Pembayaran::class);

        $user = Auth::user();

        if ($user->hasRole('PetugasRt')) {
            $gang = \App\Models\Gang::where('ketua_gang_id', $user->id)->first();
            if (!$gang) {
                return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai ketua gang. Tidak dapat menambahkan pembayaran.');
            }
        }

        $houses = House::all();
        $iuranWajib = IuranWajib::all();

        return view('rt.manage_pembayaran.create', compact('user', 'houses', 'iuranWajib'));
    }



    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'house_id' => 'required|exists:houses,id',
            // 'rt_id' => 'required|exists:rts,id',
            'collector_id' => 'required|exists:users,id',
            'iuran_wajib' => 'required|json',  // Validasi bahwa iuran_wajib adalah JSON
            'total_amount' => 'required|numeric',

        ]);

        // Menyimpan pembayaran
        $pembayaran = Pembayaran::create([
            'house_id' => $request->house_id,
            // 'rt_id' => $request->rt_id,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'collector_id' => Auth::user()->id, // Petugas penerima,
        ]);

        // Mengonversi JSON kembali menjadi array di backend
        $iuranWajib = json_decode($request->iuran_wajib, true);

        // Menyimpan detail pembayaran
        foreach ($iuranWajib as $id => $amount) {
            DetailPembayaran::create([
                'pembayaran_id' => $pembayaran->id,
                'house_id' => $request->house_id,
                'iuran_wajib_id' => $id,
                'amount' => $amount,
                'status' => 'confirmed',
            ]);
        }


        return redirect()->route('manage-rt.pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $houses = House::all();
        $iuranWajib = IuranWajib::all();
        return view('rt.manage_pembayaran.form', compact('pembayaran', 'houses', 'iuranWajib'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:manual,midtrans,xendit',
            'status' => 'required|in:confirmed,failed',
            'collector_id' => 'nullable|exists:users,id',
            'setoran_id' => 'nullable|exists:setoran_petugas,id',
            'payment_source' => 'required|in:resident,collector',
        ]);

        $pembayaran->update($request->all());

        return redirect()->route('manage-rt.pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        Pembayaran::destroy($id);
        return redirect()->route('manage-rt.pembayaran.index')->with('success', 'Pembayaran berhasil dihapus');
    }

    public function getIuranBelumDibayar($house_id)
    {
        // Ambil rt_id berdasarkan house_id
        $house = House::where('id', $house_id)->first();

        if (!$house) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rumah tidak ditemukan.'
            ], 404);
        }

        $rt_id = $house->rt_id;
        $iuranBelumDibayar = IuranWajib::leftJoin('detail_pembayarans', function ($join) use ($house_id) {
            $join->on('iuran_wajibs.id', '=', 'detail_pembayarans.iuran_wajib_id')
                ->where('detail_pembayarans.house_id', '=', $house_id);
        })
            ->whereNull('detail_pembayarans.id') // Hanya ambil iuran yang belum dibayar
            ->where('iuran_wajibs.rt_id', '=', $rt_id) // Filter berdasarkan rt_id pada iuran_wajibs
            ->select('iuran_wajibs.*')
            ->with('jenisIuran') // Memuat relasi dengan jenis_iuran
            ->get();
        // Modifikasi key jika diperlukan
        // Modifikasi key jika diperlukan
        // Modifikasi key jika diperlukan
        $iuranBelumDibayar = $iuranBelumDibayar->map(function ($item) {
            // Mengambil nama jenis_iuran yang terkait
            $item->jenis_iuran_id = $item->jenisIuran ? $item->jenisIuran->name : null;
            unset($item->jenisIuran); // Hapus relasi jika tidak ingin menampilkan relasi lengkap
            return $item;
        });
        // dd($iuranBelumDibayar);
        return response()->json([
            'status' => 'success',
            'message' => 'Semua iuran yang belum dibayar berhasil diambil.',
            'data' => $iuranBelumDibayar
        ]);
    }
}
