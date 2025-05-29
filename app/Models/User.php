<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang bisa diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'alamat',
        'password',
        'role',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang dikonversi secara otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Gunakan kolom 'phone' sebagai username/login.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'phone';
    }

    /**
     * Relasi: 1 User memiliki banyak RencanaGiling.
     */
    public function rencanaGiling()
    {
        return $this->hasMany(RencanaGiling::class);
    }
    public function rencanaPanen()
    {
        return $this->hasMany(rencanaPanen::class);
    }

    public function pengajuanGiling()
    {
        return $this->belongsToMany(RencanaGiling::class, 'petani_rencana_giling', 'petani_id', 'rencana_giling_id')
            ->withPivot('status', 'tanggal_diajukan')
            ->withTimestamps();
    }

    public function pengajuanPanen()
    {
        return $this->belongsToMany(rencanaPanenen::class, 'pabrik_rencana_panen', 'pabrik_id', 'rencana_panen_id')
            ->withPivot('status', 'tanggal_diajukan')
            ->withTimestamps();
    }

}
