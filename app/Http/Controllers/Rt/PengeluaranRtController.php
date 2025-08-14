<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;

use App\Models\PengeluaranRt;
use App\Models\Rt;
use App\Models\PengeluaranRtItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\SaldoRt;
use App\Models\SaldoPosRt;
use App\Models\TransaksiRt;


class PengeluaranRtController extends Controller
{
    public function index()
    {
        $pengeluarans = PengeluaranRt::with(['user', 'rt', 'items'])
            ->latest()
            ->paginate(10);
        // dd($pengeluarans);
        return view('rt.pengeluaran-rt.index', compact('pengeluarans'));
    }

    public function create()
    {
        $rt = Rt::where('ketua_rt_id', Auth::id())
            ->orWhere('bendahara_id', Auth::id())
            ->firstOrFail();

        return view('rt.pengeluaran-rt.create', compact('rt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'tanggal' => 'required|date',
            'nama_pencatat' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'bukti_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.catatan' => 'nullable|string|max:255',
        ]);
        // dd($validated);
        DB::beginTransaction();
        try {
            // Simpan file jika ada
            $buktiPath = null;
            if ($request->hasFile('bukti_gambar')) {
                $buktiPath = $request->file('bukti_gambar')->store('bukti_pengeluaran', 'public');
            }
            // Hitung total semua item
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['jumlah'] * $item['harga_satuan'];
            }
            // Generate nomor nota unik
            $nomorNota = 'NOTA-' . strtoupper(Str::random(10));
            // Simpan data utama
            $pengeluaran = PengeluaranRt::create([
                'rt_id' => $validated['rt_id'],
                'user_id' => auth()->id(),
                'nama_pencatat' => $validated['nama_pencatat'],
                'nomor_nota' => $nomorNota,
                'total' => $total,
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
                'bukti_gambar' => $buktiPath,
                'status_konfirmasi' => 'pending',
            ]);
            // Simpan item-item
            foreach ($validated['items'] as $item) {
                $jumlah = $item['jumlah'];
                $harga = $item['harga_satuan'];
                $totalItem = $jumlah * $harga;

                $pengeluaran->items()->create([
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'total' => $totalItem,
                    'satuan' => $item['satuan'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('manage-rt.shared.pengeluaran.index')
                ->with('success', 'Pengeluaran berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data. ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $pengeluaran = PengeluaranRt::with('items')->findOrFail($id);
        $rt = $pengeluaran->rt;

        return view('rt.pengeluaran-rt.edit', compact('pengeluaran', 'rt'));
    }


    public function update(Request $request, PengeluaranRt $pengeluaranRt)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pencatat' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'bukti_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Validasi items
            'items.*.id' => 'nullable|exists:pengeluaran_rt_items,id',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request, $pengeluaranRt) {
            // Upload gambar baru jika ada
            if ($request->hasFile('bukti_gambar')) {
                if ($pengeluaranRt->bukti_gambar && file_exists(storage_path('app/public/' . $pengeluaranRt->bukti_gambar))) {
                    unlink(storage_path('app/public/' . $pengeluaranRt->bukti_gambar));
                }
                $pengeluaranRt->bukti_gambar = $request->file('bukti_gambar')->store('bukti_pengeluaran', 'public');
            }

            // Hitung total baru
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['jumlah'] * $item['harga_satuan'];
            }

            // Update pengeluaran
            $pengeluaranRt->update([
                'nama_pencatat' => $validated['nama_pencatat'],
                'total' => $total,
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
                'bukti_gambar' => $pengeluaranRt->bukti_gambar,
            ]);

            // Hapus semua item lama, lalu simpan ulang
            $pengeluaranRt->items()->delete();
            foreach ($validated['items'] as $item) {
                $pengeluaranRt->items()->create([
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $item['jumlah'] * $item['harga_satuan'],
                    'satuan' => $item['satuan'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }
        });

        return redirect()->route('manage-rt.shared.pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy(PengeluaranRt $pengeluaranRt)
    {
        if ($pengeluaranRt->bukti_gambar && file_exists(storage_path('app/public/' . $pengeluaranRt->bukti_gambar))) {
            unlink(storage_path('app/public/' . $pengeluaranRt->bukti_gambar));
        }

        $pengeluaranRt->delete();

        return redirect()->route('manage-rt.shared.pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
    public function nota($id)
    {
        $pengeluaran = PengeluaranRt::with(['rt.ketuaRT', 'rt.bendahara', 'items', 'confirmedByUser'])->findOrFail($id);

        return view('rt.pengeluaran-rt.nota', compact('pengeluaran'));
    }
    public function approvePengeluaran($id, Request $request)
    {
        $pengeluaran = PengeluaranRt::with('rt')->findOrFail($id);

        if ($pengeluaran->status_konfirmasi === 'confirmed') {
            return back()->with('warning', 'Pengeluaran sudah dikonfirmasi.');
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $rtId = $pengeluaran->rt_id;
            $nominal = $pengeluaran->total;

            // Update status pengeluaran
            $pengeluaran->status_konfirmasi = 'confirmed';
            $pengeluaran->confirmed_by = $user->id;
            $pengeluaran->nama_validator = $user->name;
            $pengeluaran->confirmed_at = now();
            $pengeluaran->save();

            // Update saldo utama
            $saldoUtama = SaldoRt::firstOrCreate(['rt_id' => $rtId]);
            $saldoUtama->setReference($id, 'Pengeluaran');
            $saldoUtama->total_saldo -= $nominal;
            $saldoUtama->save();

            // Catat transaksi
            TransaksiRt::create([
                'rt_id' => $rtId,
                'jenis' => 'pengeluaran',
                'sumber' => 'pengeluaran_rt',
                'ref_id' => $pengeluaran->id,
                'ref_tabel' => 'pengeluaran_rts',
                'nominal' => $pengeluaran->total,
                'saldo_setelah' => $saldoUtama->total_saldo,
                'keterangan' => 'Pengeluaran RT dari nota ' . $pengeluaran->nomor_nota,
            ]);

            // Kurangi saldo POS pencatat
            $pos = null;
            if ($pengeluaran->user_id == $pengeluaran->rt->bendahara_id) {
                $pos = 'bendahara_rt';
            } elseif ($pengeluaran->user_id == $pengeluaran->rt->ketua_rt_id) {
                $pos = 'ketua_rt';
            }

            if ($pos) {
                $saldoPos = SaldoPosRt::firstOrCreate([
                    'rt_id' => $rtId,
                    'pos' => $pos,
                ], [
                    'user_id' => $pengeluaran->user_id,
                    'saldo' => 0
                ]);

                $saldoPos->saldo -= $nominal;
                $saldoPos->save();
            }

            DB::commit();

            return back()->with('success', 'Pengeluaran berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi: ' . $e->getMessage());
        }
    }
    // public function approvePengeluaran($id, Request $request)
    // {
    //     $pengeluaran = PengeluaranRt::with('rt')->findOrFail($id);
    //     $user = Auth::user();

    //     // Cek apakah sudah dikonfirmasi
    //     if ($pengeluaran->status_konfirmasi === 'confirmed') {
    //         return back()->with('warning', 'Pengeluaran sudah dikonfirmasi.');
    //     }

    //     // Cegah pencatat mengkonfirmasi sendiri
    //     if ($pengeluaran->user_id === $user->id) {
    //         return back()->with('error', 'Anda tidak dapat mengkonfirmasi pengeluaran yang Anda catat sendiri.');
    //     }

    //     // Ambil ID Ketua RT dan Bendahara dari relasi RT
    //     $ketuaRT = $pengeluaran->rt->ketua_rt_id;
    //     $bendahara = $pengeluaran->rt->bendahara_id;

    //     // Cek hanya Ketua RT atau Bendahara yang boleh konfirmasi
    //     if (!in_array($user->id, [$ketuaRT, $bendahara])) {
    //         return back()->with('error', 'Anda tidak memiliki izin untuk mengkonfirmasi pengeluaran ini.');
    //     }

    //     // Cek logika: jika pencatat adalah bendahara, maka hanya ketua yang bisa approve
    //     if ($pengeluaran->user_id === $bendahara && $user->id !== $ketuaRT) {
    //         return back()->with('error', 'Hanya Ketua RT yang dapat mengkonfirmasi pengeluaran yang dicatat oleh Bendahara.');
    //     }

    //     // Jika pencatat adalah ketua, maka hanya bendahara yang bisa approve
    //     if ($pengeluaran->user_id === $ketuaRT && $user->id !== $bendahara) {
    //         return back()->with('error', 'Hanya Bendahara yang dapat mengkonfirmasi pengeluaran yang dicatat oleh Ketua RT.');
    //     }

    //     // Lolos semua pengecekan, lakukan konfirmasi
    //     $pengeluaran->status_konfirmasi = 'confirmed';
    //     $pengeluaran->confirmed_by = $user->id;
    //     $pengeluaran->nama_validator = $user->name;
    //     $pengeluaran->confirmed_at = now();
    //     $pengeluaran->save();

    //     return back()->with('success', 'Pengeluaran berhasil dikonfirmasi.');
    // }
}
