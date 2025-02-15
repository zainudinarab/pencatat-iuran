<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\IuranWajib;
use App\Models\Rt;
use Illuminate\Http\Request;

class IuranWajibController extends Controller
{
    public function index()
    {
        $iuranWajib = IuranWajib::with('rt')->paginate(10);
        return view('rt.manage_iuran_wajib.index', compact('iuranWajib'));
    }

    public function create()
    {
        $rts = Rt::all();
        return view('rt.manage_iuran_wajib.form', compact('rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'bill_month' => 'required|integer|digits:6', // YYYYMM format
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        IuranWajib::create($request->all());

        return redirect()->route('manage-rt.iuran-wajib.index')->with('success', 'Iuran Wajib berhasil ditambahkan');
    }

    public function edit($id)
    {
        $iuranWajib = IuranWajib::findOrFail($id);
        $rts = Rt::all();
        return view('rt.manage_iuran_wajib.form', compact('iuranWajib', 'rts'));
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
