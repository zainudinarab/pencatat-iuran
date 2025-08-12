<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRtGangFilter;
use App\Traits\AutoAssignRtGangUser;
use App\Traits\HasActivityLog;


class House extends Model
{
    use HasRtGangFilter, HasActivityLog, HasFactory;

    protected $primaryKey = 'id'; // ID gabungan blok dan nomer
    public $incrementing = false; // Menggunakan primary key yang bukan auto increment
    protected $fillable = ['id', 'blok', 'nomer', 'rt_id', 'gang_id', 'name', 'address'];

    /**
     * Relasi antara House dan RT
     * Rumah dimiliki oleh satu RT
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }
    /**
     * Relasi antara House dan Gang
     * Rumah dimiliki oleh satu Gang
     * */
    public function gang()
    {
        return $this->belongsTo(Gang::class);
        // return $this->belongsTo(Gang::class, 'gang_id');
    }
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'house_id', 'id');
    }
    // protected static function booted()
    // {
    //     static::addGlobalScope(new FilterByRtGangScope);
    // }
}
