<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:profils-view')->only('index', 'show');
        $this->middleware('permission:profils-create')->only('create', 'store');
        $this->middleware('permission:profils-edit')->only('edit', 'update');
        $this->middleware('permission:profils-delete')->only('destroy');
    }


    // Menampilkan halaman profil
    public function show()
    {
        return view('profile');
    }

    // Memperbarui nama pengguna
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->back()->with('success', 'Nama berhasil diperbarui!');
    }

    // Memperbarui password pengguna
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama salah']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }
}
