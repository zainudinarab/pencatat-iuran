<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransaksiRt extends Model
{
    protected $fillable = [
        'rt_id',
        'jenis',
        'sumber',
        'ref_id',
        'ref_tabel',
        'nominal',
        'saldo_setelah',
        'keterangan',
    ];

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    public function setoran(): BelongsTo
    {
        return $this->belongsTo(SetoranPetugas::class);
    }

    public function pengeluaran(): HasOne
    {
        return $this->hasOne(PengeluaranRt::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
