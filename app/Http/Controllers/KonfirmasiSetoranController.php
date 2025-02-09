<?php
namespace App\Http\Controllers;
use App\Models\KonfirmasiSetoran;
use App\Models\Setoran;
use Illuminate\Http\Request;

class KonfirmasiSetoranController extends Controller
{
    public function confirmSetoran()
    {
        // Fetch setoran with 'pending' status
        $setorans = Setoran::with('petugas')->where('status', 'pending')->get();
    //    dd($setorans);
        // $setorans = Setoran::where('status', 'pending')->get();
        return view('setoran.confirm_setoran', compact('setorans'));
    }
    
    public function konfirmasi(Request $request, Setoran $setoran)
    {
        // Validasi input dari form
        // dd($setoran);
        $validated = $request->validate([
            'catatan' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,ditolak',
        ]);

        // Update data setoran
        $setoran->update([
            'status' => $request->status,  // Status konfirmasi
        ]);

       
        $konfirmasi = KonfirmasiSetoran::create([
            'setoran_id' => $setoran->id,
            'bendahara_id' => auth()->id(),
            'status' => $request->status, // 'diterima' or 'ditolak'
            'catatan' => $request->catatan,
        ]);

        // Redirect atau kembali dengan pesan sukses
        return redirect()->route('setoran.index')->with('success', 'Setoran berhasil dikonfirmasi');
    }
    
public function handleConfirmation(Request $request)
{
    // Find the setoran
    $setoran = Setoran::findOrFail($request->setoran_id);

    // Create a confirmation record
    $konfirmasi = KonfirmasiSetoran::create([
        'setoran_id' => $setoran->id,
        'bendahara_id' => auth()->id(),
        'status' => $request->status, // 'diterima' or 'ditolak'
        'catatan' => $request->catatan,
    ]);

    // Update the setoran status based on confirmation
    $setoran->update(['status' => $request->status == 'diterima' ? 'confirmed' : 'pending']);

    // Return a response or redirect
    return redirect()->route('confirm.setoran')->with('status', 'Setoran telah dikonfirmasi!');
}
}
