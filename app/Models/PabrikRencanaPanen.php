<?php

// app/Models/PetaniRencanaGiling.php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PetaniRencanaGiling extends Pivot
{
    protected $table = 'pabrik_rencana_panen';

    protected $fillable = ['user_id', 'rencana_panen_id', 'status'];
}
