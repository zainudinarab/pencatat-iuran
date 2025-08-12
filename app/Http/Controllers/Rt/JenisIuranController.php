<?php

namespace App\Http\Controllers\Rt;

use App\Models\JenisIuran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JenisIuranController extends Controller
{
    public function index()
    {
        $jenisIurans = JenisIuran::all();
        return view('rt.jenis_iuran.index', compact('jenisIurans'));
    }

    public function create()
    {
        return view('rt.jenis_iuran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:jenis_iurans,name',
            'amount' => 'required|numeric|min:0', // Validasi tambahan
        ]);

        JenisIuran::create([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return redirect()->route('manage-rt.jenis-iuran.index')
            ->with('success', 'Jenis Iuran berhasil ditambahkan.');
    }


    public function edit(JenisIuran $jenisIuran)
    {
        return view('rt.jenis_iuran.edit', compact('jenisIuran'));
    }

    public function update(Request $request, JenisIuran $jenisIuran)
    {
        $request->validate([
            'name' => 'required|unique:jenis_iurans,name,' . $jenisIuran->id,
        ]);

        $jenisIuran->update($request->all());

        return redirect()->route('manage-rt.jenis-iuran.index')
            ->with('success', 'Jenis Iuran berhasil diperbarui.');
    }

    public function destroy(JenisIuran $jenisIuran)
    {
        $jenisIuran->delete();
        return redirect()->route('manage-rt.jenis-iuran.index')
            ->with('success', 'Jenis Iuran berhasil dihapus.');
    }
}
