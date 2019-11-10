<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Usuario;
use Exception;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->attempt($request)) {
            return redirect()->route('main');
        }else{
            return back()->withErrors(['usuario' => trans('auth.failed')])
                        ->withInput(request(['usuario']));
        }
    }

    public function attempt(Request $request){
        try{
            $usuario = Usuario::where('usuario', '=', $request->usuario)->get()[0];

            if ($usuario->estado==1 && $usuario->password == $request->password) {
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }
}
