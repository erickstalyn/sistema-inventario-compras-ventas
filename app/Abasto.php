<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abasto extends Model
{
    protected $table = 'abasto';
    protected $fillable = [
        'proveedor_nombre', 'total', 'total_faltante', 'tipo', 'proveedor_id', 'centro_id'
    ];

    //Relación inversa de uno a muchos con proveedor
    public function getProveedor()
    {
        return $this->belongsTo('App\Persona');
    }

    //Relación de muchos a muchos con Producto
    public function getProductos()
    {
        return $this->belongsToMany('App\Producto', 'detalle_abasto', 'abasto_id', 'producto_id');
    }
    //Relacion de de uno a muchos con pagos 
    public function getPagos(){
        return $this->hasMany('App\Pago', 'abasto_id');
    }

    public function getDetalles(){
        return $this->hasMany('App\Detalle_abasto', 'abasto_id');
    }


}
