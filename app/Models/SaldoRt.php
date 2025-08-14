<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsSaldoRt;

class SaldoRt extends Model
{
    use LogsSaldoRt, HasFactory;

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
    public function logs()
    {
        return $this->hasMany(SaldoRtLog::class, 'rt_id', 'rt_id');
    }
    /**
     * Update Saldo RT (menambah atau mengurangi saldo)
     */

    // Di model SaldoRt
    protected $tempReferenceId = null;
    protected $tempReferenceType = null;

    public function setReference($id, $type)
    {
        $this->tempReferenceId = $id;
        $this->tempReferenceType = $type;
    }
}
