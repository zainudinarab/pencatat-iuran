<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoPosRt extends Model
{
    use HasFactory;

    protected $table = 'saldo_pos_rts';

    protected $fillable = [
        'rt_id',
        'pos_id',
        'saldo',
    ];

    /**
     * Relasi ke RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Relasi ke Pos
     */
    public function pos()
    {
        return $this->belongsTo(SaldoPosRt::class, 'pos_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
