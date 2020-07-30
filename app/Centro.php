<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'centro';
    protected $fillable = [
        'nombre', 'ubigeo', 'domicilio', 'telefonos', 'numeros_correlativos', 'tipo', 'tipo_anexo', 'estado'
    ];
    protected $casts = [
        'telefonos' => 'array',
        'numeros_correlativos' => 'array'
    ];

    public $timestamps = false;


    public function getSubproductos(){
        return $this->belongsToMany('App\Subproducto', 'detalle_producto')->select('nombre', 'codigo')
                    ->withPivot('id', 'stock', 'reservados', 'fallidos', 'traslado', 'precio_menor', 'precio_mayor')->as('detalle');
    }
}