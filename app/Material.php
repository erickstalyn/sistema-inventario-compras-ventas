<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';
    protected $fillable = [
        'nombre', 'subtipo', 'costo', 'unidad', 'estado'
    ];
    
    public $timestamps = false;

    //Relacion de uno a uno con Data
    public function getUnidad() {
        return $this->belongsTo('App\Data', 'unidad_id');
    }
}
