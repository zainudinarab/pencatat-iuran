<?php

namespace App\Http\Controllers\rt;

use App\Http\Controllers\Controller;
use App\Models\TransaksiRt;
use App\Models\PengeluaranRt;
use App\Traits\HandlesSaldoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengeluaranController extends Controller
{
    use HandlesSaldoLog;

    /**
     * Simpan pengeluaran baru + buat nota
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'jumlah' => 'required|numeric|min:1000',
            'penerima' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
            'rincian' => 'nullable|array',
            'rincian.*.nama' => 'required|string',
            'rincian.*.harga' => 'required|numeric',
            'rincian.*.qty' => 'required|integer',
            'bukti' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // 1. Simpan transaksi utama
                $transaksi = TransaksiRt::create([
                    'rt_id' => $validated['rt_id'],
                    'jenis' => 'pengeluaran',
                    'jumlah' => $validated['jumlah'],
                    'keterangan' => $validated['deskripsi'],
                    'dokumen_bukti' => $request->file('bukti')->store('bukti-pengeluaran'),
                    'pembuat_id' => auth()->id(),
                    'status' => 'draft' // Akan di-update setelah approval
                ]);

                // 2. Simpan nota pengeluaran
                $nota = PengeluaranRt::create([
                    'transaksi_id' => $transaksi->id,
                    'total' => $validated['jumlah'],
                    'nomor_nota' => PengeluaranRt::generateNomorNota($validated['rt_id']),
                    'tanggal' => now(),
                    'penerima' => $validated['penerima'],
                    'deskripsi' => $validated['deskripsi'],
                    'rincian' => $validated['rincian'] ?? null,
                    'dokumen' => $transaksi->dokumen_bukti
                ]);

                // 3. Jika langsung disetujui (opsional)
                if ($request->has('auto_approve')) {
                    $this->approvePengeluaran($transaksi->id);
                }
            });

            return redirect()->route('pengeluaran.index')
                ->with('success', 'Pengeluaran berhasil dicatat! Menunggu persetujuan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Approve pengeluaran dan update saldo
     */
    public function approvePengeluaran($transaksiId)
    {
        $transaksi = TransaksiRt::with('pengeluaran')->findOrFail($transaksiId);

        DB::transaction(function () use ($transaksi) {
            // 1. Update status transaksi
            $transaksi->update([
                'status' => 'disetujui',
                'penyetuju_id' => auth()->id()
            ]);

            // 2. Kurangi saldo RT (via trait)
            $this->updateSaldoAndLog(
                rtId: $transaksi->rt_id,
                amount: $transaksi->jumlah,
                type: 'pengeluaran',
                transaksiId: $transaksi->id,
                keterangan: 'Pengeluaran: ' . $transaksi->pengeluaran->deskripsi
            );
        });

        return back()->with('success', 'Pengeluaran disetujui! Saldo RT telah diperbarui.');
    }

    /**
     * Contoh menampilkan nota pengeluaran
     */
    public function showNota($id)
    {
        $pengeluaran = PengeluaranRt::with(['transaksi', 'transaksi.rt'])
            ->findOrFail($id);

        return view('pengeluaran.nota', compact('pengeluaran'));
    }
}
