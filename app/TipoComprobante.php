<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table = 'tipo_comprobante';
    protected $fillable = ['descripcion', 'numero_correlativo_max'];

    public $timestamps = false;
}
