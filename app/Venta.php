<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $fillable = ['cliente_id', 'centro_id', 'codigo', 'total', 'total_faltante', 'tipo'];
    
    public function getUsuario(){
        return $this->belongsTo('App\Usuario');
    }
    public function getProductos(){
        return $this->belongsToMany('App\Producto', 'detalle_venta')->withPivot('cantidad', 'precio', 'subtotal')->as('detalle_venta');
    }
    public function getVentaCredito(){
        return $this->hasOne('App\VentaCredito');
    }
}
