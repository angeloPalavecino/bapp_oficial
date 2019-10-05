<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriversHasDocument extends Model
{
    protected $fillable = [
        'driver_id', 'document_id','habilitado'
    ];
}
