<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'id','id_categoria','nombre', 'codigo', 'descripcion', 'precio', 'stock_total', 'estado'
    ];

    //Relación de muchos a muchos con Abasto
    public function getCategoria(){
        return $this->belongsTo('App\Categoria');
    }
    public function getEnvios(){
        return $this->belongsToMany('App\Envio', 'detalle_envio')->withPivot('cantidad')->as('detalle_envio');
    }
    public function getUsuarios(){
        return $this->belongsToMany('App\Usuario', 'detalle_producto')->withPivot('stock')->as('detalle_producto');
    }
    //Relación de muchos a muchos con Abasto
    public function getAbastos(){
        return $this->belongsToMany('App\Abasto', 'detalle_abasto')->withPivot('cantidad', 'precio', 'subtotal')->as('detalle_abasto');
    }
    public function getVentas(){
        return $this->belongsToMany('App\Venta', 'detalle_venta')->withPivot('cantidad', 'precio', 'subtotal')->as('detalle_venta');
    }

}
