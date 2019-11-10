<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'centro';
    protected $fillable = [
        'nombre', 'direccion', 'telefono', 'tipo'
    ];
}
