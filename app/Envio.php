<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envio';
    protected $fillable = [
        'usuario_from_id','usuario_to_id', 'abasto_id', 'estado'
    ];

    //Relación inversa de uno a muchos con Usuario
    public function getUsuarioEnvio()
    {
        return $this->belongsTo('App\Usuario', 'usuario_from_id');
    }
    //Relación inversa de uno a muchos con Usuario
    public function getUsuarioRecibido()
    {
        return $this->belongsTo('App\Usuario', 'usuario_to_id');
    }
    //Relación inverdsa de uno a muchos con Abasto
    public function getAbasto()
    {
        return $this->belongsTo('App\Abasto');
    }

    //Relación de muchos a muchos con Producto
    public function getProductos(){
        return $this->belongsToMany('App\Producto', 'detalle_envio')->withPivot('cantidad')->as('detalle_envio');
    }

}
