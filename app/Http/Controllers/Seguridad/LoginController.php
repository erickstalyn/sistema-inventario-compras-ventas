<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // protected $redirectTo = '/';
    // public function redirectTo(){
    //     return '/'; //o - f
    //     // return redirect()->route('login'); //e-login
    //     // return route('login'); //f
    // }

    public function index()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'usuario';
    }
 
    // public function login(Request $request)
    // {
    //     $this->validateLogin($request);

    //     if ($this->attemptLogin($request)) {
    //         // return $this->sendLoginResponse($request);
    //         return redirect('/');
    //     }

    //     $this->incrementLoginAttempts($request);

    //     return $this->sendFailedLoginResponse($request);
    // }
    

    protected function authenticated(Request $request, $user)
    {
        return false;
        // if($user->estado==1){
        //     $user->setSession();
        // }else{
        //     $this->guard()->logout();
        //     $request->session()->invalidate();
        //     return redirect('/')->withErrors(['errors' => 'EL usuario se encuentra desactivado']);
        // }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        // return $this->loggedOut($request) ?: redirect('/login');
        return $this->loggedOut($request) ?: redirect('/main');
    }
}
