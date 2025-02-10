<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSaldo extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'log_saldos';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'jenis_transaksi',
        'jumlah',
        'saldo_terakhir',
    ];

    // If you want to use timestamps, make sure you set them as true in the database or just remove this if no timestamps are required
    public $timestamps = true;

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
