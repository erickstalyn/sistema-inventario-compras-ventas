<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $fillable = [
        'categoria_id','nombre', 'codigo', 'costo_produccion', 'precio_menor', 'precio_mayor', 'stock', 'created_at'
    ];
    public $timestamps = false;
    
    //Relación de muchos a muchos con Abasto
    public function getEnvios(){
        return $this->belongsToMany('App\Envio', 'detalle_envio')->withPivot('cantidad')->as('detalle_envio');
    }
    //Relación de muchos a muchos con Abasto
    public function getAbastos(){
        return $this->belongsToMany('App\Abasto', 'detalle_abasto')->withPivot('cantidad', 'precio', 'subtotal')->as('detalle_abasto');
    }
    public function getVentas(){
        return $this->belongsToMany('App\Venta', 'detalle_venta')->withPivot('cantidad', 'precio', 'subtotal')->as('detalle_venta');
    }

}
