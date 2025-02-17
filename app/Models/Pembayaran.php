<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'total_amount',
        'payment_method',
        'status',
        'collector_id',
        'setoran_id',
        'payment_source'
    ];

    /**
     * Relasi antara Pembayaran dan House
     * Pembayaran dimiliki oleh satu House
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Relasi antara Pembayaran dan Collector
     * Pembayaran bisa memiliki satu Collector
     */
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    /**
     * Relasi antara Pembayaran dan Setoran
     * Pembayaran bisa memiliki satu Setoran
     */
    public function setoran()
    {
        return $this->belongsTo(SetoranPetugas::class, 'setoran_id');
    }

    /**
     * Relasi antara Pembayaran dan DetailPembayaran
     * Pembayaran bisa memiliki banyak DetailPembayaran
     */
    public function detailPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class);
    }
}
