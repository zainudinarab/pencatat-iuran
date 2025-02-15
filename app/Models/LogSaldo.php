<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'amount',
        'type',
        'description',
        'reference_id',
        'reference_type'
    ];

    /**
     * Relasi SaldoLog ke RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Simpan log transaksi saldo
     */
    public static function createLog($rt_id, $amount, $type, $description, $reference_id = null, $reference_type = null)
    {
        // Simpan log transaksi
        self::create([
            'rt_id' => $rt_id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'reference_id' => $reference_id,
            'reference_type' => $reference_type,
        ]);
    }
}
