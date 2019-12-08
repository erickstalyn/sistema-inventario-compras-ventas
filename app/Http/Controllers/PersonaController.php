<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class PersonaController extends Controller
{
    public function getPersona(Request $request){
        if ( !$request->ajax() ) return redirect('/');
        $documento = $request->documento;

        $persona = Persona::select('id', 'nombres', 'apellidos', 'razon_social')
                            ->where('dni', '=', $documento)
                            ->orWhere('ruc', '=', $documento)
                            ->get();
        return [
            'persona' => $persona
        ];
    }
}
