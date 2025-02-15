<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'amount',
        'description',
        'approved_by'
    ];

    /**
     * Relasi PengeluaranRt ke RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Relasi PengeluaranRt ke Bendahara
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
