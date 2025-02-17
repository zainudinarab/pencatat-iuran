<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisIuran extends Model
{
    use HasFactory;

    protected $table = 'jenis_iurans';
    protected $fillable = ['name'];

    // Relasi ke tabel iuran_wajib
    public function iuranWajibs()
    {
        return $this->hasMany(IuranWajib::class);
    }
}
