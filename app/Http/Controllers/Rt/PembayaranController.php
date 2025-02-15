<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\House;
use App\Models\IuranWajib;
use App\Models\DetailPembayaran;
use Illuminate\Http\Request;


class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['house', 'collector', 'setoran', 'detailPembayaran.iuranWajib'])->paginate(10);
        return view('rt.manage_pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $houses = House::all();

        $iuranWajib = IuranWajib::all();
        return view('rt.manage_pembayaran.form', compact('houses', 'iuranWajib'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:manual,midtrans,xendit',
            'status' => 'required|in:confirmed,failed',
            'collector_id' => 'nullable|exists:users,id',
            'setoran_id' => 'nullable|exists:setoran_petugas,id',
            'payment_source' => 'required|in:resident,collector',
        ]);

        $pembayaran = Pembayaran::create($request->all());

        // Simpan detail pembayaran berdasarkan iuran wajib
        foreach ($request->iuran_wajib as $iuranId) {
            DetailPembayaran::create([
                'pembayaran_id' => $pembayaran->id,
                'house_id' => $request->house_id,
                'iuran_wajib_id' => $iuranId,
                'amount' => $request->total_amount / count($request->iuran_wajib),
                'status' => 'pending'
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
}
