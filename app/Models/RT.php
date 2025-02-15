<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
    use HasFactory;

    protected $table = 'rts'; // Nama tabel (untuk kasus ini otomatis, bisa ditentukan jika berbeda)

    protected $fillable = [
        'name',
        'ketua_rt_id',
        'bendahara_id',
        'rw',
        'village',
        'district',
        'city'
    ];

    // Relasi ke tabel users untuk ketua_rt_id
    public function ketuaRT()
    {
        return $this->belongsTo(User::class, 'ketua_rt_id');
    }

    // Relasi ke tabel users untuk bendahara_id
    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
    public function gangs()
    {
        return $this->hasMany(Gang::class);
    }
}
