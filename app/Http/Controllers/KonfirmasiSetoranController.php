<?php

namespace App\Http\Controllers;

use App\Models\KonfirmasiSetoran;
use App\Models\Setoran;
use Illuminate\Http\Request;
use App\Models\Saldo;
use App\Models\LogSaldo;

class KonfirmasiSetoranController extends Controller
{
    public function confirmSetoran()
    {
        // Fetch setoran with 'pending' status
        $setorans = Setoran::with('petugas')->where('status', 'pending')->get();
        //    dd($setorans);
        // $setorans = Setoran::where('status', 'pending')->get();
        $konfirmasiSetorans = KonfirmasiSetoran::with(['setoran', 'bendahara', 'setoran.petugas']) // Mengambil relasi dengan setoran dan bendahara
            ->latest()
            ->paginate(10);
        return view('setoran.confirm_setoran', compact('setorans', 'konfirmasiSetorans'));
    }

    public function konfirmasi(Request $request, Setoran $setoran)
    {
        // Validasi input dari form
        // dd($setoran);
        $validated = $request->validate([
            'catatan' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,ditolak',
        ]);
        // Update data setoran
        $setoran->update([
            'bendahara_id' => auth()->user()->id,
            'status' => $request->status,  // Status konfirmasi
        ]);
        // total_amount
        $total_amount = $setoran->total_amount;
        // Update saldo jika status konfirmasi adalah 'confirmed'
        if ($request->status === 'confirmed') {
            // Ambil saldo terakhir dari tabel saldoes
            $saldo = Saldo::latest()->first(); // Ambil saldo terakhir dari tabel saldoes

            if ($saldo) {
                // Hitung saldo baru
                $new_saldo = $saldo->saldo + $total_amount;

                // Update saldo di tabel saldoes
                $saldo->update([
                    'saldo' => $new_saldo,
                ]);
            } else {
                // Jika saldo tidak ditemukan, misalnya buat saldo awal
                $new_saldo = $total_amount; // Saldo awal adalah jumlah setoran yang diterima

                // Buat record saldo awal
                Saldo::create([
                    'saldo' => $new_saldo,
                ]);
            }

            // Menambahkan log transaksi ke tabel log_saldos
            LogSaldo::create([
                'user_id' => auth()->id(), // ID pengguna yang mengkonfirmasi
                'jenis_transaksi' => 'setoran',
                'jumlah' => $total_amount,
                'saldo_terakhir' => $new_saldo,
            ]);
        }


        $konfirmasi = KonfirmasiSetoran::create([
            'setoran_id' => $setoran->id,
            'bendahara_id' => auth()->id(),
            'status' => $request->status, // 'diterima' or 'ditolak'
            'catatan' => $request->catatan,
        ]);

        // Redirect atau kembali dengan pesan sukses
        return redirect()->route('setoran.index')->with('success', 'Setoran berhasil dikonfirmasi');
    }

    public function handleConfirmation(Request $request)
    {
        // Find the setoran
        $setoran = Setoran::findOrFail($request->setoran_id);

        // Create a confirmation record
        $konfirmasi = KonfirmasiSetoran::create([
            'setoran_id' => $setoran->id,
            'bendahara_id' => auth()->id(),
            'status' => $request->status, // 'diterima' or 'ditolak'
            'catatan' => $request->catatan,
        ]);

        // Update the setoran status based on confirmation
        $setoran->update(['status' => $request->status == 'diterima' ? 'confirmed' : 'pending']);

        // Return a response or redirect
        return redirect()->route('confirm.setoran')->with('status', 'Setoran telah dikonfirmasi!');
    }
}
