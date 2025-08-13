<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Rt;
use App\Models\SaldoPosRt;
use App\Models\User;
use Illuminate\Http\Request;

class RtController extends Controller
{
    // Menampilkan daftar RT
    public function index()
    {
        $rts = Rt::paginate(10);
        return view('rt.manage_rt.index', compact('rts'));
    }
    public function create()
    {
        $users = User::all();
        return view('rt.manage_rt.form', compact('users'));
    }

    // Menampilkan detail RT
    public function show($id)
    {
        $rt = Rt::findOrFail($id);
        return view('rt.manage_rt.show', compact('rt'));
    }

    // Menambah data RT
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ketua_rt_id' => 'required|exists:users,id',
            'bendahara_id' => 'required|exists:users,id',
            'rw' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $rt = Rt::create($request->all());
        // Buat saldo pos ketua RT
        SaldoPosRt::firstOrCreate([
            'rt_id' => $rt->id,
            'pos' => 'ketua_rt',
        ], [
            'user_id' => $request->ketua_rt_id
        ]);

        // Buat saldo pos bendahara
        SaldoPosRt::firstOrCreate([
            'rt_id' => $rt->id,
            'pos' => 'bendahara_rt',
        ], [
            'user_id' => $request->bendahara_id
        ]);
        return redirect()->route('manage-rt.rts.index')->with('success', 'RT berhasil ditambahkan');
    }
    public function edit($id)
    {
        $rt = Rt::findOrFail($id);
        $users = User::all();
        return view('rt.manage_rt.form', compact('rt', 'users'));
    }
    // Mengupdate data RT
    public function update(Request $request, $id)
    {
        $rt = Rt::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'ketua_rt_id' => 'required|exists:users,id',
            'bendahara_id' => 'required|exists:users,id',
            'rw' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $rt->update($request->all());
        SaldoPosRt::updateOrCreate(
            ['rt_id' => $rt->id, 'pos' => 'ketua_rt'],
            ['user_id' => $request->ketua_rt_id]
        );

        // Update saldo pos bendahara
        SaldoPosRt::updateOrCreate(
            ['rt_id' => $rt->id, 'pos' => 'bendahara_rt'],
            ['user_id' => $request->bendahara_id]
        );

        return redirect()->route('manage-rt.rts.index')->with('success', 'RT berhasil diperbarui');
    }

    // Menghapus data RT
    public function destroy($id)
    {
        Rt::destroy($id);
        return redirect()->route('manage-rt.rts.index')->with('success', 'RT berhasil dihapus');
    }
}
