<?php

namespace App\Http\Middleware;

use Closure;

class Puesto
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if($this->permiso()){
            return $next($request);
        // }
        
    }

    // private function permiso(){
    //     return session()->get('tipoUsuario') == 2;
    // }

}
