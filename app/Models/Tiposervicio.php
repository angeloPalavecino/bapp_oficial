<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiposervicio extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'empresa_id','outsourcing', 'tipo' ,'valor',
    ];
}
