<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfirmasiSetoranPetugas extends Model
{
    use HasFactory;

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
        'setoran_id',      // ID Setoran Petugas
        'bendahara_id',    // ID Bendahara yang mengkonfirmasi
        'status',          // Status konfirmasi
        'catatan',         // Catatan dari Bendahara
    ];

    /**
     * Relasi dengan tabel setoran_petugas.
     */
    public function setoran()
    {
        return $this->belongsTo(SetoranPetugas::class, 'setoran_id');
    }

    /**
     * Relasi dengan tabel users untuk Bendahara yang mengkonfirmasi.
     */
    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
}
