<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    protected $table = 'persona';

    protected $fillable = [
        'nombre','dni', 'nombre_negocio', 'ruc', 'direccion', 'num_telefono', 'num_celular', 'email', 'observacion', 'tipo', 'segundo_tipo', 'birthday'
    ];

    public function getUsuario(){
        return $this->hasOne('App\Usuario');
    }

    public function getProveedor(){
        return $this->hasOne('App\Proveedor');
    }
}
