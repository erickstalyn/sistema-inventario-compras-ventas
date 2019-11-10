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
        'rol', 'persona_id', 'centro_id', 'usuario', 'password', 'estado'
    ];
    public $timestamps = false;
    protected $hidden = [
        'password'
    ];

    public function getPersona() {
        return $this->belongsTo('App\Persona', 'persona_id');
    }
    public function getCentro(){
        return $this->belongsTo('App\Centro', 'centro_id');
    }

    public function setSession(){
        //con this, me refiero a $user, y $user es el usuario loqueado actualmente
        //Sirve para añadir variables en la sesion
        Session::put([
            'tipoUsuario' => $this->rol
        ]);
        // dd($this->getPersona()->get()->nombre);
    }

    public function getNombres(){
        if ($this->rol == 'M') {
            return $this->getPersona->nombres.' '.$this->getPersona->apellidos;
        } else {
            return $this->getCentro->nombre;
        }
    }
}
