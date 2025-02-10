<?php
// app/Models/Pengeluaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'pengeluarans';

    // Define the fillable fields
    protected $fillable = [
        'bendahara_id',
        'amount',
        'description',
        'tanggal_pengeluaran',
    ];

    // Relationship with the User (Bendahara)
    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
}
