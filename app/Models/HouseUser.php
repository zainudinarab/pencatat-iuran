<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseUser extends Model
{
    use HasFactory;

    // Menentukan tabel yang digunakan oleh model
    protected $table = 'house_user';

    // Kolom yang bisa diisi
    protected $fillable = ['user_id', 'house_id', 'role'];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan model House
    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
