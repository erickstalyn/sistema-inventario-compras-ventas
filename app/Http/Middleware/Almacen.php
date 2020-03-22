<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Almacen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if($this->permiso()){
            return TRUE;
            // return $next($request);
        }else{
            // return redirect('logout');
            return FALSE;
        }
    }

    private function permiso(){
        return Auth::user()->getRol->id === 3;
    }

}
