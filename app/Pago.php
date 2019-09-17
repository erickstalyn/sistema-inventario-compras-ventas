<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $fillable = ['venta_credito_id', 'monto'];

    public function getVentaCredito(){
        return $this->belongsTo('App\VentaCredito');
    }
}
