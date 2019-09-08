<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name', 'lastname' ,'rut', 'email', 'telefono', 'habilitado', 'pais','ciudad', 'comuna', 'direccion', 'numeracion', 'user_id'
    ];
}


  