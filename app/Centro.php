<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'centro';
    protected $fillable = [
        'nombre', 'direccion', 'telefono', 'tipo'
    ];
    public $timestamps = false;

    public function getProductos(){
        return $this->belongsToMany('App\Producto', 'detalle_producto')->select('nombre', 'codigo')
                    ->withPivot('id', 'substock', 'reservados', 'fallidos', 'traslado', 'precio_menor', 'precio_mayor')->as('detalle');
    }
}
