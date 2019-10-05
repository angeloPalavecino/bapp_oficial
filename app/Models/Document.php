<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name', 'habilitado','type_document_id', 'url', 'fecha_vencimiento', 'informacion'
    ];
}
 