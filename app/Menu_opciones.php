<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu_opciones extends Model
{

    protected $fillable = [
        'id','productos','categorias','usuarios', 'abastecimientos', 'ventas', 'envios', 'proveedores', 'puestos', 'almacenes', 'estadisticas'
    ];

    //Relación de uno a uno con 
    public function getRol()
    {
        return $this->belongsTo('App\Rol', 'id_rol');
    }
    
}
