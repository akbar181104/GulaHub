<?php

// app/Models/PetaniRencanaGiling.php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PetaniRencanaGiling extends Pivot
{
    protected $table = 'petani_rencana_giling';

    protected $fillable = ['user_id', 'rencana_giling_id', 'status'];
}
