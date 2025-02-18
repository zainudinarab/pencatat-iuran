<?php

namespace App\Http\Controllers\Rt;



use App\Models\KonfirmasiSetoranPetugas;
use App\Models\SetoranPetugas;
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
        // Fetch setoran with 'pending' status
        $setorans = SetoranPetugas::with('collector')->where('status', 'pending')->get();

        // $setorans = Setoran::where('status', 'pending')->get();

        $KonfirmasiSetoranPetugas = KonfirmasiSetoranPetugas::with(['setoran']) // Mengambil relasi dengan setoran dan bendahara
            ->latest()
            ->paginate(10);
        return view('rt.manage_setoran.confirm_setoran', compact('setorans', 'KonfirmasiSetoranPetugas'));
    }
    public function konfirmasi(Request $request, SetoranPetugas $setoran)
    {
        $user = Auth::user();
        $roles = $user->role_with_rt;
        dd($roles);
        $rtId = null;
        $isBendahara = false;

        foreach ($roles as $role) {
            if ($role->role == 'bendahara-rt') {
                $rtId = $role->rt_id;
                $isBendahara = true;
                break; // Tidak perlu lanjutkan pencarian setelah menemukan bendahara
            }
        }
        if (!$isBendahara) {
            return redirect()->route('manage-rt/confirm-setoran-petugas'); // Ganti dengan route halaman index yang sesuai
        }
        $validated = $request->validate([
            'catatan' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,ditolak',
        ]);
        dd($request->all());
        // Update data setoran
        $SetoranPetugas->update([
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
    public function index()
    {
        // Mengambil semua konfirmasi setoran petugas
        $konfirmasis = KonfirmasiSetoranPetugas::with(['setoran', 'bendahara'])->get();

        return view('konfirmasi_setoran.index', compact('konfirmasis'));
    }

    /**
     * Menyimpan konfirmasi setoran petugas baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'setoran_id' => 'required|exists:setoran_petugas,id', // Memastikan setoran_id valid
            'bendahara_id' => 'required|exists:users,id', // Memastikan bendahara_id valid
            'status' => 'required|in:pending,confirmed,ditolak', // Status valid
            'catatan' => 'nullable|string', // Catatan opsional
        ]);

        // Simpan konfirmasi setoran
        KonfirmasiSetoranPetugas::create($validated);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('konfirmasi_setoran.index')->with('success', 'Konfirmasi setoran berhasil disimpan.');
    }

    /**
     * Mengubah status konfirmasi setoran petugas.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi data input
        $validated = $request->validate([
            'status' => 'required|in:confirmed,ditolak', // Status valid
            'catatan' => 'nullable|string', // Catatan opsional
        ]);

        // Cari konfirmasi setoran berdasarkan ID
        $konfirmasi = KonfirmasiSetoranPetugas::findOrFail($id);

        // Update status dan catatan konfirmasi
        $konfirmasi->update($validated);

        // Redirect ke halaman konfirmasi setoran dengan pesan sukses
        return redirect()->route('konfirmasi_setoran.index')->with('success', 'Status konfirmasi setoran berhasil diperbarui.');
    }

    /**
     * Menghapus konfirmasi setoran petugas.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Cari konfirmasi setoran berdasarkan ID
        $konfirmasi = KonfirmasiSetoranPetugas::findOrFail($id);

        // Hapus konfirmasi setoran
        $konfirmasi->delete();

        // Redirect ke halaman konfirmasi setoran dengan pesan sukses
        return redirect()->route('konfirmasi_setoran.index')->with('success', 'Konfirmasi setoran berhasil dihapus.');
    }
}
