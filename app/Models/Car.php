<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'tipo', 'marca' ,'modelo', 'ano', 'motor', 'patente', 'color','asientos', 'padron', 'permiso', 'permiso_fin', 'revision', 'revision_fin', 'mtt', 'mtt_fin','driver_id'
    ];
}
