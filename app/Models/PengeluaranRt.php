<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengeluaranRt extends Model
{
    protected $fillable = [
        'transaksi_id',
        'total',
        'nomor_nota',
        'tanggal',
        'penerima',
        'deskripsi',
        'rincian',
        'dokumen'
    ];

    protected $casts = [
        'rincian' => 'array',
        'tanggal' => 'date'
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiRt::class);
    }

    // Method generate nomor nota (tambahkan ini)
    public static function generateNomorNota($rtId): string
    {
        $rtCode = 'RT-' . str_pad($rtId, 2, '0', STR_PAD_LEFT);
        $date = now()->format('Ymd');
        $lastNota = static::where('nomor_nota', 'like', "NOTA/{$rtCode}/{$date}%")->latest()->first();

        $sequence = $lastNota ? (int) substr($lastNota->nomor_nota, -3) + 1 : 1;

        return "NOTA/{$rtCode}/{$date}/" . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
