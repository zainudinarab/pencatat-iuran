<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\User;
use App\Models\HouseUser;
use Illuminate\Http\Request;

class HouseUserController extends Controller
{
    // Method lainnya untuk CRUD rumah sudah ada, kita menambahkannya di sini
    public function index()
    {
        // Mengambil data HouseUser dengan relasi user dan house
        // Menggunakan paginate untuk membatasi jumlah data yang ditampilkan
        // Anda dapat mengubah 10 menjadi jumlah yang Anda inginkan
        // Contoh: $houseUsers = HouseUser::with(['user', 'house'])->paginate(10);
        $houseUsers = HouseUser::with(['user', 'house'])->paginate(10);

        return view('Rt.house_user.index', compact('houseUsers'));
    }
    public function create()
    {
        $houses = House::all();
        $users = User::all();
        return view('Rt.house-user.create', compact('houses', 'users'));
    }
    public function show($id)
    {
        // Mencari data HouseUser berdasarkan ID
        $houseUser = HouseUser::findOrFail($id);

        // Atau jika ingin mengambil data terkait user dan rumah:
        $user = $houseUser->user;
        $house = $houseUser->house;

        // Mengirim data ke view
        return view('rt.house_user.show', compact('houseUser', 'user', 'house'));
    }
    public function store(Request $request) {}
    public function edit(HouseUser $houseUser) {}
    public function update(Request $request, HouseUser $houseUser) {}
    public function destroy(HouseUser $houseUser) {}

    public function linkUserToHouse()
    {
        // Menampilkan halaman untuk menghubungkan user dengan rumah
        $houses = House::all(); // Daftar rumah yang sudah ada
        $users = User::all(); // Daftar user yang sudah ada
        return view('rt.house_user.link', compact('houses', 'users'));
    }

    public function storeLink(Request $request)
    {
        // Validasi input untuk memastikan bahwa user dan rumah yang dipilih valid
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'house_id' => 'required|exists:houses,id',
            'role' => 'required|in:pemilik,penyewa',
        ]);

        // Menyimpan hubungan user dengan rumah pada tabel pivot
        HouseUser::create([
            'user_id' => $request->user_id,
            'house_id' => $request->house_id,
            'role' => $request->role,
        ]);

        return redirect()->route('manage-rt.house-user.index')->with('success', 'User berhasil dihubungkan dengan rumah.');
    }
}
