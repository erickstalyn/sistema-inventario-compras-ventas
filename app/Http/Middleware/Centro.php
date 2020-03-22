<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Centro
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ( Auth::user()->getRol->id != 2 && Auth::user()->getRol->id != 3 ){
            return redirect('logout');
        }

        return $next($request);
    }
}
