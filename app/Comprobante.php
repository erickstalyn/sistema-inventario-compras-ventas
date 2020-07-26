<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobante';
    protected $fillable = ['numero_serie', 'numero_correlativo', 'tipo_comprobante', 'venta_id', 'tipo_comprobante_id'];
}
