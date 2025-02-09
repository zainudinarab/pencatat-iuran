<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoKas extends Model
{
    protected $fillable = ['total_saldo'];

    public function logSaldo()
    {
        return $this->hasMany(LogSaldo::class);
    }
}
