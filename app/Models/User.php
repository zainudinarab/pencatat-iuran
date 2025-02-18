<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function penarikan()
    {
        return $this->hasMany(Penarikan::class, 'petugas_id');
    }

    public function setoran()
    {
        return $this->hasMany(Setoran::class, 'bendahara_id');
    }
    public function resident()
    {
        return $this->hasOne(Resident::class);
    }
    public function rtKetua()
    {
        return $this->hasOne(RT::class, 'ketua_rt_id');
    }

    public function rtBendahara()
    {
        return $this->hasOne(RT::class, 'bendahara_id');
    }


    // Relasi dengan Gang (Ketua Gang, atau Petugas RT)
    public function gang()
    {
        return $this->hasOne(Gang::class, 'ketua_gang_id');
    }

    // Logika untuk mendapatkan peran berdasarkan relasi RT dan Gang
    public function roleWithRT()
    {
        // Pastikan relasi RT dan Gang sudah dimuat
        $this->loadMissing('rtKetua', 'rtBendahara', 'gang');

        // Cek apakah user adalah Ketua RT
        if ($this->rtKetua) {
            return ['role' => 'ketua-rt', 'rt_id' => $this->rtKetua->id];
        }

        // // Cek apakah user adalah Bendahara RT
        // if ($this->rtBendahara) {
        //     return ['role' => 'bendahara-rt', 'rt_id' => $this->rtBendahara->id];
        // }

        // Cek apakah user adalah Ketua Gang (Petugas RT)
        if ($this->gang) {
            return ['role' => 'petugas-rt', 'rt_id' => $this->gang->rt_id, 'gang_id' => $this->gang->id];
        }

        // Jika bukan pengurus RT atau Gang, anggap sebagai warga
        return ['role' => 'warga', 'rt_id' => null];
    }

    public function getRoleWithRtAttribute()
    {
        $this->loadMissing('rtKetua', 'rtBendahara', 'gang');
        $roles = [];
    
        if ($this->rtKetua) {
            $roles[] = ['role' => 'ketua-rt', 'rt_id' => $this->rtKetua->id];
        }
    
        if ($this->rtBendahara) {
            $roles[] = ['role' => 'bendahara-rt', 'rt_id' => $this->rtBendahara->id];
        }
    
        if ($this->gang) {
            $roles[] = ['role' => 'petugas-rt', 'rt_id' => $this->gang->rt_id];
        }
    
        if (empty($roles)) {
            $roles[] = ['role' => 'warga', 'rt_id' => null];
        }
    
        return $roles;
    }
    // public function gang()
    // {
    //     return $this->hasOne(Gang::class, 'ketua_gang_id');
    // }

    // public function rt()
    // {
    //     return $this->hasOneThrough(RT::class, Gang::class, 'ketua_gang_id', 'id', 'id', 'rt_id');
    // }

    // public function roleWithRT()
    // {
    //     // Pastikan relasi rt dan gang sudah dimuat
    //     $this->loadMissing('rt', 'gang');

    //     // Cek apakah user adalah Ketua RT
    //     if ($this->rt && $this->id == $this->rt->ketua_rt_id) {
    //         return ['role' => 'ketua-rt', 'rt_id' => $this->rt->id];
    //     }
    //     // Cek apakah user adalah Bendahara RT
    //     if ($this->rt && $this->id == $this->rt->bendahara_id) {
    //         return ['role' => 'bendahara-rt', 'rt_id' => $this->rt->id];
    //     }
    //     // Cek apakah user adalah Ketua Gang (Petugas RT)
    //     if ($this->gang) {
    //         return ['role' => 'petugas-rt', 'rt_id' => $this->gang->rt_id, 'gang_id' => $this->gang->id];
    //     }
    //     // Jika bukan pengurus, anggap sebagai warga
    //     return ['role' => 'warga', 'rt_id' => null];
    // }
}
