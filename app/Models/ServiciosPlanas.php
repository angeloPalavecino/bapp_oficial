<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiciosPlanas extends Model
{
    //
    protected $fillable = [
        'empresa_id', 'desde' ,'hasta', 'valormin', 'valorpsjadicional' ,'tipo', 'fz1', 'fz2' ,'fz3',
    ];
}
