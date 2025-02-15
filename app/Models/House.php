<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // ID gabungan blok dan nomer
    public $incrementing = false; // Menggunakan primary key yang bukan auto increment
    protected $fillable = ['id', 'blok', 'nomer', 'rt_id', 'address'];

    /**
     * Relasi antara House dan RT
     * Rumah dimiliki oleh satu RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }
}
