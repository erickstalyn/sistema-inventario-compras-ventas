<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subproducto extends Model
{
    protected $table = 'subproducto';
    protected $fillable = [
        'producto_id', 'nombre', 'code_unique', 'codigo', 'descripcion', 'caracteristicas', 'caracteristicas_json', 'costo_produccion', 'precio_menor', 'precio_mayor', 'stock', 'estado'
    ];
    protected $casts = [
        'caracteristicas_json' => 'array'
    ];

    protected $timestamps = false;
    
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
