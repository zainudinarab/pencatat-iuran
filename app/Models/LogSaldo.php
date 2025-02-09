<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSaldo extends Model
{
    protected $fillable = ['saldo_kas_id', 'jenis_transaksi', 'jumlah', 'keterangan'];

    public function saldoKas()
    {
        return $this->belongsTo(SaldoKas::class);
    }
}
