<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\IuranWajib;
use App\Models\Rt;
use Carbon\Carbon;

use App\Models\JenisIuran;
use Illuminate\Http\Request;

class IuranWajibController extends Controller
{
    public function index()
    {
        $iuranWajib = IuranWajib::with('rt', 'jenisIuran')->paginate(10);
        return view('rt.manage_iuran_wajib.index', compact('iuranWajib'));
    }

    public function create()
    {
        $jenisIuran = JenisIuran::all();
        $months = [];
        $start = Carbon::now()->startOfMonth(); // Mulai dari bulan ini
        $end = $start->copy()->addYears(2); // Sampai 2 tahun ke depan

        while ($start <= $end) {
            $months[] = [
                'value' => $start->format('Y-m'),
                'label' => $start->isoFormat('MMMM YYYY'), // Format: Februari 2025
            ];
            $start->addMonth();
        }
        $rts = Rt::all();
        return view('rt.manage_iuran_wajib.form', compact('rts', 'months', 'jenisIuran'));
    }

    public function store(Request $request)

    {

        $request->validate([
            'rt_id' => 'required|integer',
            'jenis_iuran_id' => 'required|exists:jenis_iurans,id',
            'start_month' => 'required|date_format:Y-m',
            'end_month' => 'nullable|date_format:Y-m|after_or_equal:start_month', // Opsional
            'amount' => 'required|numeric',
        ]);
        // Ambil data dari request
        $rtId = $request->input('rt_id');
        $jenisIuranId = $request->input('jenis_iuran_id');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $amount = $request->input('amount');
        if (!$endMonth) {
            $endMonth = $startMonth;
        }
        // Generate bulan-bulan dalam rentang
        $start = Carbon::createFromFormat('Y-m', $startMonth);
        $end = Carbon::createFromFormat('Y-m', $endMonth);
        // Batasi rentang maksimal 2 tahun (24 bulan)
        if ($start->diffInMonths($end) > 24) {
            return redirect()->back()
                ->with('error', 'Rentang bulan tidak boleh lebih dari 2 tahun.');
        }
        // $billMonth = Carbon::createFromFormat('Y-m', $request->input('bill_month'))->format('Ym');


        while ($start <= $end) {
            // Format bill_month ke YYYYMM
            $billMonth = $start->format('Ym');

            // Cek apakah iuran sudah ada untuk RT, jenis iuran, dan bulan yang sama
            $existingIuran = IuranWajib::where('rt_id', $rtId)
                ->where('jenis_iuran_id', $jenisIuranId)
                ->where('bill_month', $billMonth)
                ->exists();

            if ($existingIuran) {
                return redirect()->back()
                    ->with('error', "Iuran untuk jenis ini sudah ada di bulan {$start->isoFormat('MMMM YYYY')}.");
            }

            // Simpan iuran wajib
            IuranWajib::create([
                'rt_id' => $rtId,
                'jenis_iuran_id' => $jenisIuranId,
                'bill_month' => $billMonth,
                'amount' => $amount,
            ]);


            $start->addMonth();
        }

        return redirect()->route('manage-rt.iuran-wajib.index')->with('success', 'Iuran Wajib berhasil ditambahkan');
    }

    public function edit($id)
    {
        $iuranWajib = IuranWajib::findOrFail($id);
        $rts = Rt::all();
        $jenisIuran = JenisIuran::all();

        // Generate ulang $months (sama seperti di create)
        $months = [];
        $start = Carbon::now()->startOfMonth();
        $end = $start->copy()->addYears(2);

        while ($start <= $end) {
            $months[] = [
                'value' => $start->format('Ym'), // Pastikan format sama: Ym (contoh: 202502)
                'label' => $start->translatedFormat('F Y'), // Format lokal: Februari 2025
            ];
            $start->addMonth();
        }

        return view('rt.manage_iuran_wajib.form', compact('iuranWajib', 'rts', 'jenisIuran', 'months'));
    }

    public function update(Request $request, $id)
    {
        $iuranWajib = IuranWajib::findOrFail($id);

        $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'bill_month' => 'required|integer|digits:6',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        $iuranWajib->update($request->all());

        return redirect()->route('manage-rt.iuran-wajib.index')->with('success', 'Iuran Wajib berhasil diperbarui');
    }

    public function destroy($id)
    {
        IuranWajib::destroy($id);
        return redirect()->route('manage-rt.iuran-wajib.index')->with('success', 'Iuran Wajib berhasil dihapus');
    }
}
