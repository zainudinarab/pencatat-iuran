<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembayaran_id',
        'house_id',
        'iuran_wajib_id',
        'amount',
        'status'
    ];

    /**
     * Relasi antara DetailPembayaran dan Pembayaran
     * DetailPembayaran dimiliki oleh satu Pembayaran
     */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    /**
     * Relasi antara DetailPembayaran dan IuranWajib
     * DetailPembayaran dimiliki oleh satu IuranWajib
     */
    public function iuranWajib()
    {
        return $this->belongsTo(IuranWajib::class);
    }
}
