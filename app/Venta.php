<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $fillable = ['cliente_id', 'centro_id', 'codigo', 'total', 'total_faltante', 'tipo'];
    
    public function getDetalleVenta(){
        return $this->hasMany('App\Detalle_venta');
    }
}
