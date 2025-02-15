<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Gang extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'ketua_gang_id',
        'name',
    ];

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function ketuaGang()
    {
        return $this->belongsTo(User::class, 'ketua_gang_id');
    }
}
