<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;

class Usuario extends Authenticatable
{

    protected $table= 'usuario';
    protected $remember_token = false;
    protected $fillable = [
        'rol_id', 'usuario', 'password', 'estado', 'persona_id'
    ];

    protected $hidden = [
        'password'
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
        return $this->belongsTo('App\Persona', 'persona_id');
    }

    public function setSession(){
        //con this, me refiero a $user, y $user es el usuario loqueado actualmente
        //Sirve para añadir variables en la sesion
        // Session::put([
            
        // ]);
        // dd($this->getPersona()->get()->nombre);
    }

}
