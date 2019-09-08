<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'produccion_id','facturacion_id','rut', 'dv' ,'razon_social', 'giro',
    ];
}
