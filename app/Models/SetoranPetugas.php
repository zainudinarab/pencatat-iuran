<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranPetugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'collector_id',
        'rt_id',
        'total_amount',
        'status',
        'approved_by'
    ];

    /**
     * Relasi SetoranPetugas ke Petugas (Collector)
     */
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    /**
     * Relasi SetoranPetugas ke RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Relasi SetoranPetugas ke Bendahara (yang menyetujui)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
