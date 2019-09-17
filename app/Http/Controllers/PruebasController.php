<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class PruebasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mostrar()
    {
        return $usuario = Usuario::find(1)->getPersona->nombre;
    }

    
}
