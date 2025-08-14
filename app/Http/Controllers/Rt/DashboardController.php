<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SaldoRt;
use App\Models\SaldoPosRt;
use App\Models\TransaksiRt;
use App\Models\Rt;
use App\Models\House;
use App\Models\Pembayaran;
use App\Models\Gang;
use App\Models\SetoranWarga; // contoh: jika petugas input setoran
// jika warga punya data profil

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Coba ambil RT dari kolom ketua_rt_id atau bendahara_id
        $rt = Rt::where('ketua_rt_id', $user->id)
            ->orWhere('bendahara_id', $user->id)
            ->orWhere('id', $user->rt_id) // Kalau user warga atau sudah diset secara langsung
            ->first();

        // Jika belum ketemu, cek apakah user adalah petugas (ketua_gang)
        if (!$rt) {
            $gang = Gang::where('ketua_gang_id', $user->id)->first();

            if ($gang) {
                // Ambil RT dari gang
                $rt = $gang->rt;
            }
        }

        // Kalau setelah semua itu masih tidak ketemu RT, tolak akses
        if (!$rt) {
            abort(403, 'Anda belum terdaftar di RT manapun.');
        }

        // Cek role berdasarkan posisi di RT
        if ($rt->ketua_rt_id === $user->id) {
            return $this->dashboardKetuaRt($user, $rt, $this->getSaldoRt($rt->id));
        }

        if ($rt->bendahara_id === $user->id) {
            return $this->dashboardBendahara($user, $rt, $this->getSaldoRt($rt->id));
        }

        // Jika bukan ketua atau bendahara, cek role dari sistem (Spatie)
        $role = $user->getRoleNames()->first();

        switch ($role) {
            case 'warga':
                return $this->dashboardWarga($user, $rt, $this->getSaldoRt($rt->id));
            case 'petugas_rt':
                return $this->dashboardPetugas($user, $rt, $this->getSaldoRt($rt->id));
            default:
                abort(403, 'Role tidak dikenali.');
        }
    }

    private function getSaldoRt($rt_id)
    {
        return SaldoRt::where('rt_id', $rt_id)->first();
    }



    private function dashboardWarga($user, $rt, $saldoRt)
    {
        // Misal: warga bisa lihat saldo RT dan riwayat setorannya
        $setoran = TransaksiRt::where('rt_id', $rt->id)
            ->where('ref_tabel', 'setoran_wargas')
            ->where('ref_id', $user->id) // asumsi ref_id = user_id
            ->latest()
            ->limit(5)
            ->get();

        return view('rt.dashboard.warga', compact('rt', 'saldoRt', 'setoran'));
    }

    private function dashboardPetugas($user, $rt, $saldoRt)
    {
        // Total setoran hari ini
        $totalSetoranHariIni = TransaksiRt::where('rt_id', $rt->id)
            ->where('sumber', 'setoran_petugas')
            ->where('ref_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('nominal');

        // Transaksi RT terbaru
        $transaksiTerbaru = TransaksiRt::where('rt_id', $rt->id)
            ->where('sumber', 'setoran_petugas')
            ->where('ref_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // ✅ Ambil Riwayat Pembayaran dari tabel "pembayarans"
        $riwayatPembayaran = Pembayaran::where('collector_id', $user->id)
            ->where('status', 'confirmed') // hanya yang valid
            ->orderByDesc('created_at')
            ->get();

        // Hitung total sudah & belum disetor
        $totalBelumDisetor = $riwayatPembayaran->whereNull('setoran_id')->sum('total_amount');
        $totalSudahDisetor = $riwayatPembayaran->whereNotNull('setoran_id')->sum('total_amount');

        return view('rt.dashboard.petugas', compact(
            'rt',
            'saldoRt',
            'totalSetoranHariIni',
            'transaksiTerbaru',
            'riwayatPembayaran',
            'totalBelumDisetor',
            'totalSudahDisetor'
        ));
    }

    private function dashboardBendahara($user, $rt, $saldoRt)
    {
        // Bendahara: kelola keuangan, lihat semua transaksi
        $saldoPos = SaldoPosRt::where('rt_id', $rt->id)
            ->where('user_id', $user->id)
            ->where('pos', 'bendahara_rt')
            ->first();

        $transaksi = TransaksiRt::where('rt_id', $rt->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('rt.dashboard.bendahara', compact('rt', 'saldoRt', 'saldoPos', 'transaksi'));
    }

    private function dashboardKetuaRt($user, $rt, $saldoRt)
    {
        // Ketua RT: monitoring, laporan, semua saldo
        $saldoPos = SaldoPosRt::where('rt_id', $rt->id)
            ->where('user_id', $user->id)
            ->where('pos', 'ketua_rt')
            ->first();

        $totalWarga = House::where('rt_id', $rt->id)->count(); // asumsi ada tabel warga
        $transaksi = TransaksiRt::where('rt_id', $rt->id)
            ->with('user') // siapa yang input
            ->latest()
            ->limit(10)
            ->get();

        return view('rt.dashboard.rt', compact('rt', 'saldoRt', 'saldoPos', 'totalWarga', 'transaksi'));
    }
}
