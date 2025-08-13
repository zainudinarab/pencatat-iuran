<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatTransferPosRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'pengirim_pos',
        'penerima_pos',
        'jumlah',
        'nama_pengirim',
        'nama_penerima',
        'status',
        'confirmed_at',
        'keterangan',
        'created_by',
    ];
    protected $casts = [
        'confirmed_at' => 'datetime',
    ];
    public function rtPengirim()
    {
        return $this->belongsTo(Rt::class, 'rt_id_pengirim');
    }

    public function rtPenerima()
    {
        return $this->belongsTo(Rt::class, 'rt_id_penerima');
    }

    public function posPengirim()
    {
        return $this->belongsTo(SaldoPosRt::class, 'pos_id_pengirim');
    }

    public function posPenerima()
    {
        return $this->belongsTo(SaldoPosRt::class, 'pos_id_penerima');
    }

    public function userKonfirmasi()
    {
        return $this->belongsTo(User::class, 'user_konfirmasi_id');
    }
}
