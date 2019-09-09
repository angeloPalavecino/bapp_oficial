<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Car;

class Driver extends Model
{
    protected $fillable = [
        'name', 'lastname' ,'rut', 'email', 'telefono', 'habilitado', 'pais','ciudad', 'comuna', 'direccion', 'numeracion', 'clase', 'carnet_fin', 'antecedentes_fin', 'licencia_fin','user_id'
    ];

    public function cars()
    {
      return $this->hasMany(Car::class);
    }
}


  