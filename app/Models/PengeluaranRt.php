<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengeluaranRt extends Model
{
    protected $table = 'pengeluaran_rts';

    protected $fillable = [
        'rt_id',
        'user_id',
        'nomor_nota',
        'nama_pencatat',
        'total',
        'tanggal',
        'catatan',
        'bukti_gambar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'User tidak tersedia'
        ]);
    }
public function rt()
{
    return $this->belongsTo(Rt::class, 'rt_id');
}

    /**
     * Relasi ke item-item pengeluaran
     */
    public function items()
    {
        return $this->hasMany(PengeluaranRtItem::class, 'pengeluaran_rt_id');
    }

    /**
 * Generate nomor nota unik per RT per hari
 */
protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (empty($model->nomor_nota)) {
            $model->nomor_nota = static::generateNomorNota($model->rt_id);
        }
    });
}

public static function generateNomorNota($rtId): string
{
    $rtCode = 'RT-' . str_pad($rtId, 2, '0', STR_PAD_LEFT);
    $date = now()->format('Ymd');
    $lastNota = static::where('nomor_nota', 'like', "NOTA/{$rtCode}/{$date}%")->latest()->first();

    $sequence = $lastNota ? (int) substr($lastNota->nomor_nota, -3) + 1 : 1;

    return "NOTA/{$rtCode}/{$date}/" . str_pad($sequence, 3, '0', STR_PAD_LEFT);
}


}
