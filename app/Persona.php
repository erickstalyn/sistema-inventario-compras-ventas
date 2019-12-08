<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    protected $table = 'persona';

    protected $fillable = [
        'nombres', 'apellidos', 'razon_social', 'dni', 'ruc', 'direccion', 'telefono', 'email', 'birthday', 'observacion', 'tipo', 'cliente', 'proveedor', 'persona_id'
    ];

    public $timestamps = false;

    public function getUsuario(){
        return $this->hasOne('App\Usuario', 'persona_id');
    }

}
