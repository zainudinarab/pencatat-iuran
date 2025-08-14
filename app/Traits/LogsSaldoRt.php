<?php

namespace App\Traits;

use App\Models\SaldoRtLog;

trait LogsSaldoRt
{
    public static function bootLogsSaldoRt()
    {
        static::updating(function ($model) {
            // Cek apakah total_saldo berubah
            if ($model->isDirty('total_saldo')) {
                $saldoSebelum = $model->getOriginal('total_saldo');
                $saldoSesudah = $model->total_saldo;
                $amount = $saldoSesudah - $saldoSebelum;
                $type = $amount > 0 ? 'pemasukan' : 'pengeluaran';

                // Simpan log saldo
                SaldoRtLog::create([
                    'rt_id' => $model->rt_id,
                    'amount' => abs($amount),
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $saldoSesudah,
                    'type' => $type,
                    'description' => 'Update saldo otomatis',
                    'reference_id' => $model->tempReferenceId,
                    'reference_type' => $model->tempReferenceType,
                ]);
            }
        });
    }

    public function logSaldoChange($amount, $type, $description, $referenceId = null, $referenceType = null)
    {
        $saldoSebelum = $this->total_saldo;

        // Update saldo utama
        $this->total_saldo += $amount;
        $this->save();

        // Simpan log
        SaldoRtLog::create([
            'rt_id' => $this->rt_id,
            'amount' => $amount,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $this->total_saldo,
            'type' => $type,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }
}
