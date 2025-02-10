<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'saldoes';

    // Define the fillable fields
    protected $fillable = ['saldo'];

    // If you want to use timestamps, make sure you set them as true in the database or just remove this if no timestamps are required
    public $timestamps = true;
}
