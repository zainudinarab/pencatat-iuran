<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransaksiRt extends Model
{
    protected $fillable = [
        'rt_id',
        'setoran_id',
        'jenis',
        'jumlah',
        'keterangan',
        'dokumen_bukti',
        'pembuat_id',
        'penyetuju_id',
        'status'
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
}
