<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $fillable = ['bendahara_id', 'total_amount', 'tanggal_setoran', 'status'];

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }

    public function penarikan()
    {
        return $this->hasMany(Penarikan::class);
    }
}
