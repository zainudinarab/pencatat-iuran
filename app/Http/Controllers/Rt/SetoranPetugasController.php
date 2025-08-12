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
        $userId = Auth::id();
        $setoran = SetoranPetugas::with('collector', 'rt', 'approvedBy')
            ->where('collector_id', $userId)
            ->paginate(10);

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
            // 'rt_id' => 'required|exists:rts,id',
            'pembayaran_ids' => 'required|array',
            'total_amount' => 'required|numeric',
        ]);

        $setoran = SetoranPetugas::create([
            'collector_id' => $request->collector_id,
            // 'rt_id' => $request->rt_id,
            'total_amount' => $request->total_amount,
            'status' => 'pending', // Status setoran baru adalah 'pending'
            'approved_by' => null, // Belum disetujui
        ]);

        Pembayaran::whereIn('id', $request->pembayaran_ids)
            ->update(['setoran_id' => $setoran->id]);


        return redirect()->route('manage-rt.setoran-petugas.index')->with('success', 'Setoran petugas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $setoran = SetoranPetugas::findOrFail($id);

        // Ambil semua pembayaran collector yg masih milik dia atau yang sudah dipilih sebelumnya
        $pembayarans = Pembayaran::where(function ($query) use ($setoran) {
            $query->whereNull('setoran_id')
                ->orWhere('setoran_id', $setoran->id);
        })->where('collector_id', $setoran->collector_id)
            ->with('collector', 'house')
            ->get();

        $selectedPembayaranIds = $setoran->pembayarans()->pluck('id')->toArray();

        return view('rt.manage_setoran.edit', compact('setoran', 'pembayarans', 'selectedPembayaranIds'));
    }



    public function update(Request $request, $id)
    {
        $setoran = SetoranPetugas::findOrFail($id);

        $this->authorize('update', $setoran);

        $request->validate([
            'collector_id' => 'required|exists:users,id',
            // 'rt_id' => 'required|exists:rts,id',
            'tanggal_setoran' => 'required|date',
            'pembayaran_ids' => 'required|array',
            'total_amount' => 'required|numeric',
        ]);

        // Ambil semua pembayaran yang sebelumnya terhubung ke setoran ini
        $pembayaranSebelumnya = Pembayaran::where('setoran_id', $setoran->id)->pluck('id')->toArray();

        // Ambil yang sekarang dikirim dari form
        $pembayaranBaru = $request->pembayaran_ids;

        // Hitung yang dihapus (tidak dicentang lagi)
        $toUnlink = array_diff($pembayaranSebelumnya, $pembayaranBaru);

        // Hitung yang ditambahkan (baru dicentang)
        $toLink = array_diff($pembayaranBaru, $pembayaranSebelumnya);

        // Unlink pembayaran yang di-uncheck
        if (!empty($toUnlink)) {
            Pembayaran::whereIn('id', $toUnlink)->update(['setoran_id' => null]);
        }

        // Link pembayaran baru
        if (!empty($toLink)) {
            Pembayaran::whereIn('id', $toLink)->update(['setoran_id' => $setoran->id]);
        }

        // Update data setoran
        $setoran->update([
            'collector_id'     => $request->collector_id,
            // 'rt_id'            => $request->rt_id,
            'tanggal_setoran'  => $request->tanggal_setoran,
            'total_amount'     => $request->total_amount,
            'status'           => 'pending',
            'approved_by'      => null,
        ]);

        return redirect()->route('manage-rt.setoran-petugas.index')->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $setoran = SetoranPetugas::findOrFail($id);

        $this->authorize('delete', $setoran); // <- tambahkan ini

        $setoran->delete();

        return redirect()->route('manage-rt.setoran-petugas.index')->with('success', 'Setoran petugas berhasil dihapus');
    }


    // public function destroy($id)
    // {
    //     SetoranPetugas::destroy($id);
    //     $this->authorize('delete', $setoran); // <- tambahkan ini
    //     return redirect()->route('manage-rt.setoran.index')->with('success', 'Setoran petugas berhasil dihapus');
    // }
}
