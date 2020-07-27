<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $fillable = ['accion', 'fecha', 'ip', 'registro_id', 'tabla', 'detalles', 'usuario_id'];
    protected $casts = [
        'detalle' => 'array'
    ];

    public $timestamps = false;
}
