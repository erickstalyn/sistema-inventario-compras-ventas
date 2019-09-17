<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    public $timestamps = false;
    protected $table='rol';
    protected $fillable = [
        'menu_opciones_id','nombre'
    ];

    //Relación de muchos a muchos con Usuario
    public function getUsuarios(){
        return $this->hasMany('App\Usuario');
    }

    //Relacion de uno a uno con Menu_opciones
    public function getMenu_opciones()
    {
        return $this->hasOne('App\Menu_opciones');
    }
}
