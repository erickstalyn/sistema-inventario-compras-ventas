<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abasto extends Model
{
    protected $table= 'abasto';
    protected $fillable = [
        'id_usuario','id_proveedor', 'total', 'estado'
    ];

    //Relación de uno a muchos con Envio
    public function getEnvios()
    {
        return $this->hasMany('App\Envio');
    }
    //Relación inversa de uno a muchos con proveedor
    public function getProveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    //Relación de muchos a muchos con Producto
    public function getProductos()
    {
        return $this->belongsToMany('App\Producto', 'detalle_abasto');
    }

}
