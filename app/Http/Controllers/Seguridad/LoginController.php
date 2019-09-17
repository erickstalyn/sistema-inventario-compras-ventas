<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = 'main';

    public function index()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'usuario';
    }
 
    

    protected function authenticated(Request $request, $user)
    {
        if($user->estado==1){
            $user->setSession();
        }else{
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login')->withErrors(['errors' => 'EL usuario se encuentra desactivado']);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
