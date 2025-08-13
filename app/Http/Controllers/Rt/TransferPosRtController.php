<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\SaldoPosRt;
use App\Models\RiwayatTransferPosRt;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TransferPosRtController extends Controller
{
    /**
     * Buat transfer dari satu pos ke pos lain
     */
    public function index()
    {
        $user = auth()->user();
        // Cari RT dimana user adalah ketua_rt atau bendahara
        $rt = Rt::where('ketua_rt_id', $user->id)
            ->orWhere('bendahara_id', $user->id)
            ->first();
        if (!$rt) {
            return redirect()->back()->with('error', 'Data RT tidak ditemukan untuk user ini.');
        }

        // Tentukan posisi user
        if ($user->id == $rt->ketua_rt_id) {
            $posUser = 'ketua_rt';
        } elseif ($user->id == $rt->bendahara_id) {
            $posUser = 'bendahara_rt';
        } else {
            return redirect()->back()->with('error', 'Anda bukan ketua RT atau bendahara.');
        }

        // Ambil saldo pos user berdasarkan posUser dan rt_id
        $posSaldo = SaldoPosRt::where('rt_id', $rt->id)
            ->where('pos', $posUser)
            ->first();

        if (!$posSaldo) {
            return redirect()->back()->with('error', 'Saldo pos Anda tidak ditemukan.');
        }

        // Ambil transfer yang status pending dan penerima adalah user login
        // Ambil saldo pos penerima berdasar pos dan rt_id
        $penerimaSaldo = SaldoPosRt::where('user_id', $user->id)->first();

        // if ($penerimaSaldo) {
        //     $pos = $penerimaSaldo->pos;
        //     $rtId = $penerimaSaldo->rt_id;

        //     $transfers = RiwayatTransferPosRt::with([
        //         'posPengirim',
        //         'posPenerima',
        //         'userKonfirmasi'
        //     ])
        //         ->where('status', 'pending')
        //         ->where('rt_id', $rtId)
        //         // ->where('penerima_pos', $pos)  // Filter berdasarkan posisi penerima yang sesuai user login
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        //     //
        // } else {
        //     $transfers = collect(); // atau handle error
        // }
        // Transfer yang siap diterima
        $transferSiapTerima = RiwayatTransferPosRt::with(['posPengirim', 'posPenerima', 'userKonfirmasi'])
            ->where('status', 'pending')
            ->where('rt_id', $rt->id)
            // ->where('penerima_pos', $posUser)
            ->orderBy('created_at', 'desc')
            ->get();

        // Riwayat semua transfer selain pending
        $riwayatTransfer = RiwayatTransferPosRt::with(['posPengirim', 'posPenerima', 'userKonfirmasi'])
            ->where('rt_id', $rt->id)
            ->where(function ($query) use ($posUser) {
                $query->where('pengirim_pos', $posUser)
                    ->orWhere('penerima_pos', $posUser);
            })
            ->where('status', '!=', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();


        // Pos pengirim untuk form transfer cuma satu (posUser)
        $posList = collect([$posSaldo]);
        // dd($posSaldo->pos);
        // Kirim data ke view
        // return view('rt.transfer_pos.index', compact('transfers', 'posList', 'posSaldo'));
        return view('rt.transfer_pos.index', compact('transferSiapTerima', 'riwayatTransfer', 'posList', 'posSaldo'));
    }





    public function store(Request $request)
    {

        $user = Auth::user();

        $rt = Rt::where('ketua_rt_id', $user->id)
            ->orWhere('bendahara_id', $user->id)
            ->first();

        if (!$rt) {
            return redirect()->back()->with('error', 'RT tidak ditemukan untuk user ini.');
        }
        $rtId = $rt->id;

        $request->validate([
            'pengirim_pos' => 'required|in:bendahara_rt,ketua_rt',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $penerimaPos = $request->pengirim_pos === 'bendahara_rt' ? 'ketua_rt' : 'bendahara_rt';

        try {
            DB::transaction(function () use ($rtId, $user, $request, $penerimaPos) {
                $jumlah = $request->jumlah;

                $pengirim = SaldoPosRt::where('rt_id', $rtId)
                    ->where('pos', $request->pengirim_pos)
                    ->lockForUpdate()
                    ->firstOrFail();

                $penerima = SaldoPosRt::where('rt_id', $rtId)
                    ->where('pos', $penerimaPos)
                    ->firstOrFail();

                if ($pengirim->saldo < $jumlah) {
                    throw new \Exception('Saldo pengirim tidak mencukupi.');
                }

                // Kurangi saldo pengirim saat ini juga
                $pengirim->decrement('saldo', $jumlah);

                RiwayatTransferPosRt::create([
                    'rt_id' => $rtId,
                    'pengirim_pos' => $request->pengirim_pos, // harus diisi
                    'penerima_pos' => $penerimaPos,           // harus diisi
                    'jumlah' => $jumlah,
                    'nama_pengirim' => $pengirim->user->name,
                    'nama_penerima' => $penerima->user->name,
                    'status' => 'pending',
                    'keterangan' => $request->keterangan,
                    'created_by' => $user->id,
                ]);
            });

            return back()->with('success', 'Transfer berhasil diajukan dan saldo dikurangi.');
        } catch (\Exception $e) {
            // Tangani semua kesalahan dan tampilkan notifikasi error
            return back()->with('error', 'Transfer gagal: ' . $e->getMessage());
        }
    }


    /**
     * Konfirmasi transfer oleh penerima
     */
    public function konfirmasi($id)
    {
        DB::transaction(function () use ($id) {
            $transfer = RiwayatTransferPosRt::where('id', $id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->firstOrFail();

            $saldoPenerima = SaldoPosRt::where('rt_id', $transfer->rt_id)
                ->where('pos', $transfer->penerima_pos)
                ->lockForUpdate()
                ->firstOrFail();

            // Tambahkan saldo ke penerima
            $saldoPenerima->increment('saldo', $transfer->jumlah);

            $transfer->update([
                'status' => 'disetujui',
                'confirmed_at' => now(),
            ]);
        });

        return back()->with('success', 'Transfer berhasil dikonfirmasi.');
    }
    public function tolak($id)
    {
        DB::transaction(function () use ($id) {
            $transfer = RiwayatTransferPosRt::where('id', $id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->firstOrFail();

            // Ambil saldo pengirim
            $saldoPengirim = SaldoPosRt::where('rt_id', $transfer->rt_id)
                ->where('pos', $transfer->pengirim_pos)
                ->lockForUpdate()
                ->firstOrFail();

            // Kembalikan dana ke pengirim
            $saldoPengirim->increment('saldo', $transfer->jumlah);

            // Update status transfer ke 'ditolak'
            $transfer->update([
                'status' => 'ditolak',
                'confirmed_at' => now(),
            ]);
        });

        return back()->with('success', 'Transfer berhasil ditolak dan dana dikembalikan.');
    }
}
