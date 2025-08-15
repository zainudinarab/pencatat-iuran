<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rt;
use App\Models\Pembayaran;
use App\Models\Gang;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil semua RT beserta petugasnya
        $rts = Rt::with(['ketuaRt', 'bendahara', 'gangs.ketuaGang'])->get();

        foreach ($rts as $rt) {
            $petugasIds = $rt->semuaPetugas()->pluck('id');

            $query = Pembayaran::whereIn('collector_id', $petugasIds);

            $rt->total_penerimaan = (clone $query)->sum('jumlah');
            $rt->total_setor = (clone $query)->whereNotNull('setoran_id')->sum('jumlah');
            $rt->total_belum_setor = (clone $query)->whereNull('setoran_id')->sum('jumlah');
        }

        return view('laporan.index', compact('rts'));
    }
    public function laporanPetugas()
    {
        $rts = Rt::with(['ketuaRt', 'bendahara', 'gangs.ketuaGang'])->get();

        foreach ($rts as $rt) {
            $petugasList = $rt->semuaPetugas();

            foreach ($petugasList as $petugas) {
                $query = Pembayaran::where('collector_id', $petugas->id)
                    ->where('rt_id', $rt->id)
                    ->where('status', 'confirmed');

                $petugas->total_penerimaan   = (clone $query)->sum('total_amount');
                $petugas->total_setor        = (clone $query)->whereNotNull('setoran_id')->sum('total_amount');
                $petugas->total_belum_setor  = (clone $query)->whereNull('setoran_id')->sum('total_amount');
            }

            $rt->petugasList = $petugasList;
        }

        return view('rt.laporan.petugas', compact('rts'));
    }
    public function detailPetugas($id)
    {
        $petugas = User::findOrFail($id);

        // Ambil pembayaran yang dikumpulkan petugas ini
        $pembayarans = Pembayaran::where('collector_id', $petugas->id)
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

        return view('rt.laporan.petugas_detail', compact('petugas', 'pembayarans'));
    }
    public function showDetail($id)
    {
        $pembayaran = Pembayaran::with([
            'detailPembayarans.iuranWajib.jenisIuran' // Eager load sampai ke jenis iuran
        ])->findOrFail($id);

        return view('rt.laporan.detail-pembayaran', compact('pembayaran'));
    }
    public function laporanTahunanPerGang()
    {
        $driver = \DB::getDriverName(); // Dapatkan jenis DB: 'sqlite' atau 'mysql'

        // Pilih fungsi berdasarkan driver
        $yearExpression = $driver === 'sqlite'
            ? "strftime('%Y', created_at)"
            : "YEAR(created_at)";

        $data = Pembayaran::selectRaw("$yearExpression as tahun, gang_id, SUM(total_amount) as total")
            // ->where('rt_id', auth()->user()->rt_id) // filter sesuai RT
            ->groupBy('tahun', 'gang_id')
            ->orderByDesc('tahun')
            ->get()
            ->groupBy('tahun');

        $gangs = Gang::pluck('name', 'id');

        $totalPerTahun = [];

        foreach ($data as $tahun => $items) {
            $totalPerTahun[$tahun] = $items->sum('total');
        }
        // dd($data);
        return view('rt.laporan.tahunan-per-gang', compact('data', 'gangs', 'totalPerTahun'));
    }
    public function detailTahunanPerGang($tahun, $gang_id)
    {
        $pembayarans = Pembayaran::whereYear('created_at', $tahun)
            ->where('gang_id', $gang_id)
            // ->where('rt_id', auth()->user()->rt_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $gangName = Gang::find($gang_id)?->name ?? 'Tidak diketahui';

        return view('rt.laporan.detail-tahunan-per-gang', compact('pembayarans', 'tahun', 'gangName'));
    }
}
