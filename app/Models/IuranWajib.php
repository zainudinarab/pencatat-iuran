<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranWajib extends Model
{
    use HasFactory;

    protected $fillable = ['rt_id', 'jenis_iuran_id', 'bill_month', 'amount'];

    /**
     * Relasi antara IuranWajib dan RT
     * IuranWajib dimiliki oleh satu RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }
    public function jenisIuran()
    {
        return $this->belongsTo(JenisIuran::class);
    }
}
