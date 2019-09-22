<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';

    protected $fillable = [
        'nombre', 'dni', 'ruc', 'direccion', 'telefono', 'email', 'birthday', 'observacion', 'tipo'
    ];

    public function getUsuario(){
        return $this->hasOne('App\Usuario');
    }

    public function getProveedor(){
        return $this->hasOne('App\Proveedor');
    }
}
