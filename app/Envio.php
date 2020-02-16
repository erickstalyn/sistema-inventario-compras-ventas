<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envio';
    protected $fillable = [
        'centro_from_id','centro_to_id', 'abasto_id', 'estado', 'tipo', 'created_at', 'updated_at'
    ];
    public $timestamps = false;

    //Relación inversa de uno a muchos con Usuario
    public function getCentroEnvio()
    {
        return $this->belongsTo('App\Centro', 'centro_from_id');
    }
    //Relación inversa de uno a muchos con Usuario
    public function getCentroRecibido()
    {
        return $this->belongsTo('App\Centro', 'centro_to_id');
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

    public function getDetalles(){
        return $this->hasMany('App\Detalle_envio', 'envio_id');
    }

}
