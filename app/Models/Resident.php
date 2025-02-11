<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    // Menentukan kolom primary key dan tipe data
    protected $primaryKey = 'id';
    public $incrementing = false; // Menyatakan bahwa ID tidak auto increment
    protected $keyType = 'string'; // Menyatakan tipe data ID adalah string

    protected $fillable = [
        'id', 'name', 'phone_number', 'slug', 'blok', 'nomor_rumah', 'RT', 'RW', 'address',
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
