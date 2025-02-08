<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = ['name', 'address'];

    public function penarikan()
    {
        return $this->hasMany(Penarikan::class);
    }
}
