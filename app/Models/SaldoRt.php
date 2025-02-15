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
    public static function updateSaldo($rt_id, $amount, $type)
    {
        // Cek apakah sudah ada saldo untuk RT ini
        $saldo = self::firstOrCreate(['rt_id' => $rt_id]);

        // Update saldo berdasarkan jenis transaksi
        if ($type == 'income') {
            $saldo->total_saldo += $amount; // Menambah saldo jika income
        } elseif ($type == 'expense') {
            $saldo->total_saldo -= $amount; // Mengurangi saldo jika expense
        }

        // Simpan perubahan saldo
        $saldo->save();

        return $saldo;
    }
}
