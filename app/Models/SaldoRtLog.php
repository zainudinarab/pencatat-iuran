<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoRtLog extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'saldo_rt_logs';

    // Menentukan kolom mana yang boleh diisi secara mass-assignment
    protected $fillable = [
        'rt_id',
        'amount',
        'type',
        'description',
        'reference_id',
        'reference_type'
    ];

    // Definisi relasi dengan model RT
    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    // Jika SaldoRtLog memiliki referensi dengan model lain, misal Pembayaran atau Pengeluaran
    public function reference()
    {
        return $this->morphTo();
    }
}
