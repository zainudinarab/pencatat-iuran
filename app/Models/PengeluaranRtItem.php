<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranRtItem extends Model
{
      protected $fillable = [
        'pengeluaran_rt_id',
        'nama_item',
        'jumlah',
        'harga_satuan',
        'total',
        'satuan',
        'catatan'
    ];

    public function pengeluaranRt()
    {
        return $this->belongsTo(PengeluaranRt::class);
    }
    //
}
