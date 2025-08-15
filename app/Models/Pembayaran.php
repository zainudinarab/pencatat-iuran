<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRtGangFilter;
use App\Traits\AutoAssignRtGangUser;
use App\Traits\HasActivityLog;


class Pembayaran extends Model
{
    use HasRtGangFilter, AutoAssignRtGangUser, HasActivityLog, HasFactory;

    protected $fillable = [
        'house_id',
        'rt_id',
        'gang_id',
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
        return $this->belongsTo(House::class, 'house_id', 'id');
    }
    public function gang()
    {
        return $this->belongsTo(Gang::class);
        // return $this->belongsTo(Gang::class, 'gang_id');
    }
    // public function pembayarans()
    // {
    //     return $this->hasMany(Pembayaran::class, 'house_id', 'id');
    // }

    /**
     * Relasi antara Pembayaran dan Collector
     * Pembayaran bisa memiliki satu Collector
     */
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
    public function rt()
    {
        return $this->belongsTo(Rt::class);
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
    public function detailPembayarans()
    {
        return $this->hasMany(DetailPembayaran::class);
    }
    public function details()
    {
        return $this->hasMany(DetailPembayaran::class, 'pembayaran_id');
    }
}
