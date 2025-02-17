<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Rt;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::with('rt', 'gang')->paginate(10);
        return view('rt.manage_house.index', compact('houses'));
    }

    public function create()
    {
        $rts = Rt::all();
        return view('rt.manage_house.form', compact('rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:255|unique:houses,id',
            'blok' => 'required|string|max:1',
            'nomer' => 'required|integer',
            'rt_id' => 'required|exists:rts,id',
            'address' => 'required|string|max:255',
        ]);

        House::create($request->all());

        return redirect()->route('manage-rt.houses.index')->with('success', 'Rumah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $house = House::findOrFail($id);
        $rts = Rt::all();
        return view('rt.manage_house.form', compact('house', 'rts'));
    }

    public function update(Request $request, $id)
    {
        $house = House::findOrFail($id);

        $request->validate([
            'id' => 'required|string|max:255|unique:houses,id,' . $house->id,
            'blok' => 'required|string|max:1',
            'nomer' => 'required|integer',
            'rt_id' => 'required|exists:rts,id',
            'address' => 'required|string|max:255',
        ]);

        $house->update($request->all());

        return redirect()->route('manage-rt.houses.index')->with('success', 'Rumah berhasil diperbarui');
    }

    public function destroy($id)
    {
        House::destroy($id);
        return redirect()->route('manage-rt.houses.index')->with('success', 'Rumah berhasil dihapus');
    }
}
