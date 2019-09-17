<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta_credito extends Model
{
    protected $table = 'venta_credito';
    protected $fillable = ['venta_id', 'deudor', 'monto_faltante'];
    public $timestamps = false;

    public function getVenta() {
        return $this->belongsTo('App\Venta');
    }
    public function getPagos(){
        return $this->hasMany('App\Pago');
    }
}
