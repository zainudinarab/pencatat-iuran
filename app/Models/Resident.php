<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'slug',
        'blok',
        'nomor_rumah',
        'RT',
        'RW',
        'address',
    ];

    public function penarikan()
    {
        return $this->hasMany(Penarikan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
