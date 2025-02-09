<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfirmasiSetoran extends Model
{
    protected $fillable = ['setoran_id', 'bendahara_id', 'status', 'catatan'];

    public function setoran()
    {
        return $this->belongsTo(Setoran::class);
    }

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
}
