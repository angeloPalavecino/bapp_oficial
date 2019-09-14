<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiciosKMS extends Model
{
    protected $fillable = [
        'empresa_id', 'valor' ,'valorportico', 'distancia', 'tiempo' ,'bajabandera','pasajeros'
    ];
}
