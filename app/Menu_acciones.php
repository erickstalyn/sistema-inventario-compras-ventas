<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu_acciones extends Model
{
    protected $table = 'menu_acciones';
    protected $fillable = ['usuario_id', 'menu_opciones_id', 'nombre', 'listar', 'agregar', 'editar', 'eliminar'];
    public $timestamps = false;

    //Relacion inversa de uno a muchos con Usuario
    public function getUsuario(){
        return $this->belongsTo('App\Usuario');
    }
    //Relacion inversa de uno a muchos con Menu de Opciones
    public function getMenuOpciones(){
        return $this->belongsTo('App\MenuOpciones');
    }

    
}
