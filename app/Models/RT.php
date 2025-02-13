<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RT extends Model
{
    use HasFactory;

    protected $table = 'rts';

    protected $fillable = [
        'name',
        'ketua_rt_id',
        'bendahara_id',
        'rw',
        'village',
        'district',
        'city',
    ];
}
