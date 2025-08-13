<?php

namespace App\Traits;

use App\Models\SaldoRt;
use App\Models\SaldoRtLog;

trait HandlesSaldo
{
    public function updateSaldoAndLog($rtId, $amount, $type, $transaksiId = null, $keterangan = null)
    {
        DB::transaction(function () use ($rtId, $amount, $type, $transaksiId, $keterangan) {
            $saldoRt = SaldoRt::firstOrCreate(['rt_id' => $rtId]);

            $saldoRt->saldo = ($type === 'pemasukan')
                ? $saldoRt->saldo + $amount
                : $saldoRt->saldo - $amount;
            $saldoRt->save();

            SaldoRtLog::create([
                'saldo_rt_id' => $saldoRt->id,
                'transaksi_id' => $transaksiId,
                'perubahan' => ($type === 'pemasukan') ? $amount : -$amount,
                'saldo_setelah' => $saldoRt->saldo,
                'keterangan' => $keterangan
            ]);
        });
    }
}
