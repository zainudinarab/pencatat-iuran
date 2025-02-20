<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'total_saldo'
    ];

    /**
     * Relasi SaldoRt ke RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Update Saldo RT (menambah atau mengurangi saldo)
     */
    public function logs()
    {
        return $this->hasMany(SaldoRtLog::class);
    }
}
