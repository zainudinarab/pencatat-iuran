<?php

namespace App\Http\Controllers\Rt;



use App\Models\KonfirmasiSetoranPetugas;
use App\Models\SetoranPetugas;
use App\Models\SaldoRt;
use App\Models\SaldoRtLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class KonfirmasiSetoranPetugasController extends Controller
{
    /**
     * Menampilkan daftar konfirmasi setoran petugas.
     *
     * @return \Illuminate\View\View
     */
    public function confirmSetoran()
    {
        $user = Auth::user();
        $roles = $user->role_with_rt;
        // dd($roles);
        $rtId = null;
        $isBendahara = false;
        // $SetoranPetugas = [];
        foreach ($roles as $role) {
            if ($role['role'] == 'bendahara-rt' || $role['role'] == 'ketua-rt') {
                $role['role'] == 'bendahara-rt' ? $isBendahara = true : $isBendahara = false;
                $rtId = $role['rt_id'];
            }
        }
        $SetoranPetugas = SetoranPetugas::with('collector')->where('status', 'pending')
            ->where('rt_id', $rtId)
            ->get();

        $KonfirmasiSetoranPetugas = KonfirmasiSetoranPetugas::with(['setoran', 'rt', 'bendahara']) // Mengambil relasi dengan setoran dan bendahara
            ->where('rt_id', $rtId)
            ->latest()
            ->paginate(10);
        return view('rt.manage_setoran.confirm_setoran', compact('isBendahara', 'SetoranPetugas', 'KonfirmasiSetoranPetugas'));
    }
    public function konfirmasi(Request $request, SetoranPetugas $setoran)
    {
        $request->validate([
            'catatan' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,ditolak',
        ]);
        $user = Auth::user();
        $roles = $user->role_with_rt;
        $rtId = null;
        $isBendahara = false;
        foreach ($roles as $role) {
            if ($role['role'] == 'bendahara-rt') {
                $rtId = $role['rt_id'];
                $isBendahara = true;
                break; // Tidak perlu lanjutkan pencarian setelah menemukan bendahara
            }
        }
        if (!$isBendahara) {
            return redirect()->route('manage-rt.confirm.setoran.petugas')->with('error', 'Bukan bendahara RT.'); // Ganti dengan route halaman index yang sesuai
        }
        $SetoranPetugas = SetoranPetugas::where('id', $setoran->id)
            ->update([
                'approved_by' => Auth::user()->id,
                'status' => $request->status,  // Status konfirmasi
            ]);

        // total_amount
        $total_amount = $setoran->total_amount;

        // Update saldo jika status konfirmasi adalah 'confirmed'
        if ($request->status === 'confirmed') {
            // Ambil saldo terakhir dari tabel saldoes
            $SaldoRt = SaldoRt::where('rt_id', $rtId)->latest()->first(); // Ambil saldo terakhir dari tabel saldoes

            if ($SaldoRt) {
                // Hitung saldo baru
                $new_saldo = $SaldoRt->total_saldo + $total_amount;

                // Update saldo di tabel saldoes
                $SaldoRt->update([
                    'total_saldo' => $new_saldo,
                ]);
            } else {
                // Jika saldo tidak ditemukan, misalnya buat saldo awal
                $new_saldo = $total_amount; // Saldo awal adalah jumlah setoran yang diterima

                // Buat record saldo awal
                SaldoRt::create([
                    'rt_id' => $rtId,
                    'total_saldo' => $new_saldo,
                ]);
            }

            // Menambahkan log transaksi ke tabel log_saldos
            SaldoRtLog::create([
                'rt_id' => $rtId,
                'amount' => $setoran->total_amount,
                'type' => 'pemasukan',
                'description' => 'Setoran dari ' . $setoran->collector->name,
                'reference_id' => $setoran->id,
                'reference_type' => 'SetoranPetugas',
            ]);
        }
        $konfirmasi = KonfirmasiSetoranPetugas::create([
            'rt_id' => $rtId,
            'setoran_id' => $setoran->id,
            'bendahara_id' => Auth::user()->id,
            'status' => $request->status, // 'diterima' or 'ditolak'
            'catatan' => $request->catatan,
        ]);
        // Redirect atau kembali dengan pesan sukses
        return redirect()->route('manage-rt.confirm.setoran.petugas')->with('success', 'Setoran berhasil dikonfirmasi');
    }
}
