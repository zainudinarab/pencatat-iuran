<?php

// app/Http/Controllers/PengeluaranController.php
namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Saldo;
use App\Models\LogSaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:pengeluarans-view')->only('index', 'show');
        $this->middleware('permission:pengeluarans-create')->only('create', 'store');
        $this->middleware('permission:pengeluarans-edit')->only('edit', 'update');
        $this->middleware('permission:pengeluarans-delete')->only('destroy');
    }
    public function index()
    {
        // Get all pengeluarans for the authenticated user
        $pengeluarans = Pengeluaran::where('bendahara_id', Auth::id())->get();
        // Ambil saldo terakhir
        $latestSaldo = Saldo::latest()->first()->saldo ?? 0;
        return view('pengeluaran.index', compact('pengeluarans', 'latestSaldo'));
    }

    public function create()
    {
        // Return view to create a new pengeluaran
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        // Validasi input pengeluaran
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        // Simpan pengeluaran baru
        $pengeluaran = Pengeluaran::create([
            'bendahara_id' => Auth::id(),
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
        ]);

        // Perbarui saldo dan buat log saldo setelah menyimpan pengeluaran
        $this->updateSaldoAndCreateLog($pengeluaran->amount);
        // Redirect to the pengeluaran list with success message
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil disimpan');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        // Return view to edit the pengeluaran
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        // Validasi input pengeluaran yang akan diupdate
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        // Ambil jumlah pengeluaran lama untuk dikoreksi pada saldo
        $oldAmount = $pengeluaran->amount;

        // Perbarui pengeluaran
        $pengeluaran->update([
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
        ]);

        // Perbarui saldo dan buat log saldo setelah mengubah pengeluaran
        $this->updateSaldoAndCreateLogAfterUpdate($oldAmount, $validated['amount']);

        // Redirect to the pengeluaran list with success message
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        // Ambil jumlah pengeluaran yang akan dihapus
        $amountToDelete = $pengeluaran->amount;
        // Delete the pengeluaran
        $pengeluaran->delete();
        // Hapus log yang terkait dengan pengeluaran yang dihapus
        LogSaldo::where('jenis_transaksi', 'pengeluaran')
            ->where('jumlah', $amountToDelete)
            ->where('user_id', Auth::id())
            ->delete();
        // Perbarui saldo dan buat log saldo setelah penghapusan pengeluaran
        $this->updateSaldoAndCreateLogAfterDelete($amountToDelete);

        // Redirect with success message
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
    private function updateSaldoAndCreateLogAfterDelete($amount)
    {
        // Ambil saldo terakhir
        $saldo = Saldo::latest()->first();

        // Jika saldo ada, tambahkan jumlah pengeluaran yang dihapus kembali
        if ($saldo) {
            $new_saldo = $saldo->saldo + $amount;
            $saldo->update([
                'saldo' => $new_saldo,
            ]);
        }

        // Buat log saldo baru untuk pengeluaran yang dihapus
        // LogSaldo::create([
        //     'user_id' => Auth::id(),
        //     'jenis_transaksi' => 'pengeluaran',
        //     'jumlah' => $amount,
        //     'saldo_terakhir' => $saldo->saldo,
        // ]);
    }
    private function updateSaldoAndCreateLog($amount)
    {
        // Ambil saldo terakhir
        $saldo = Saldo::latest()->first();

        // Jika saldo ada, kurangi dengan jumlah pengeluaran
        if ($saldo) {
            $new_saldo = $saldo->saldo - $amount;
            $saldo->update([
                'saldo' => $new_saldo,
            ]);
        } else {
            // Jika tidak ada saldo sebelumnya, buat saldo baru
            $new_saldo = -$amount;
            Saldo::create([
                'saldo' => $new_saldo,
            ]);
        }

        // Buat log saldo
        LogSaldo::create([
            'user_id' => Auth::id(),
            'jenis_transaksi' => 'pengeluaran',
            'jumlah' => $amount,
            'saldo_terakhir' => $new_saldo,
        ]);
    }

    // Helper method untuk memperbarui saldo setelah update pengeluaran
    private function updateSaldoAndCreateLogAfterUpdate($oldAmount, $newAmount)
    {
        // Ambil saldo terakhir
        $saldo = Saldo::latest()->first();

        // Jika saldo ada
        if ($saldo) {
            // Pertama, kembalikan saldo yang sudah dikurangi oleh pengeluaran lama
            $saldo->update([
                'saldo' => $saldo->saldo + $oldAmount,
            ]);

            // Sekarang, kurangi saldo dengan pengeluaran yang baru
            $new_saldo = $saldo->saldo - $newAmount;
            $saldo->update([
                'saldo' => $new_saldo,
            ]);
        } else {
            // Jika saldo tidak ada, buat saldo baru (dengan pengeluaran baru)
            $new_saldo = -$newAmount;
            Saldo::create([
                'saldo' => $new_saldo,
            ]);
        }

        // Hapus log saldo yang lama terkait pengeluaran ini
        LogSaldo::where('jenis_transaksi', 'pengeluaran')
            ->where('jumlah', $oldAmount)
            ->where('user_id', Auth::id())
            ->delete();

        // Buat log saldo baru untuk pengeluaran yang diupdate
        LogSaldo::create([
            'user_id' => Auth::id(),
            'jenis_transaksi' => 'pengeluaran',
            'jumlah' => $newAmount,
            'saldo_terakhir' => $new_saldo,
        ]);
    }
}
