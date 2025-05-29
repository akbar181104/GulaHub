<?php

// app/Models/RencanaGiling.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaGiling extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kebutuhan_giling',
        'tanggal',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pabrik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

// Petani yang mengajukan ke rencana ini
    public function pengaju()
    {
        return $this->belongsToMany(User::class, 'petani_rencana_giling', 'rencana_giling_id', 'petani_id')
            ->withPivot('status', 'tanggal_diajukan')
            ->withTimestamps();
    }

    public function pengajuanGiling()
    {
        return $this->belongsToMany(RencanaGiling::class, 'petani_rencana_giling')
            ->withPivot('status')
            ->withTimestamps();
    }
}
