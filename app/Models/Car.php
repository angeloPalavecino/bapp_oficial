<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'tipo', 'marca' ,'modelo', 'ano', 'motor', 'patente', 'color','asientos', 'habilitado','driver_id'
    ];
}
