<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    protected $fillable = ['petugas_id', 'resident_id', 'amount', 'tanggal_penarikan', 'setoran_id'];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function setoran()
    {
        return $this->belongsTo(Setoran::class);
    }
}
