<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{

    protected $table='usuario';
    protected $fillable = [
        'rol_id', 'usuario', 'clave', 'estado'
    ];

    protected $hidden = [
        'clave'
    ];

    //Relación inversa de uno a muchos con Rol
    public function getRol(){
        return $this->belongsTo('App\Rol');
    }

    //Relación de uno a muchos con Envio
    public function getEnviosEnviados()
    {
        return $this->hasMany('App\Envio','usuario_from_id');
    }

    //Relación de uno a muchos con Envio
    public function getEnviosRecibidos()
    {
        return $this->hasMany('App\Envio','usuario_to_id');
    }

    public function getPersona(){
        return $this->belongsTo('App\Persona');
    }


}
