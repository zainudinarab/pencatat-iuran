<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Gang;
use App\Models\Rt;
use App\Models\User;
use Illuminate\Http\Request;

class GangController extends Controller
{
    public function index()
    {
        $gangs = Gang::with('rt', 'ketuaGang')->paginate(10);
        return view('rt.manage_gang.index', compact('gangs'));
    }

    public function create()
    {
        $rts = Rt::all();
        $users = User::all();
        return view('rt.manage_gang.form', compact('rts', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'ketua_gang_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        Gang::create($request->all());

        return redirect()->route('manage-rt.gangs.index')->with('success', 'Gang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $gang = Gang::findOrFail($id);
        $rts = Rt::all();
        $users = User::all();
        return view('rt.manage_gang.form', compact('gang', 'rts', 'users'));
    }

    public function update(Request $request, $id)
    {
        $gang = Gang::findOrFail($id);

        $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'ketua_gang_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        $gang->update($request->all());

        return redirect()->route('manage-rt.gangs.index')->with('success', 'Gang berhasil diperbarui');
    }

    public function destroy($id)
    {
        Gang::destroy($id);
        return redirect()->route('manage-rt.gangs.index')->with('success', 'Gang berhasil dihapus');
    }
}
