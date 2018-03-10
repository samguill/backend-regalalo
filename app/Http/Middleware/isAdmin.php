<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
        //Valida si es administrador para acceder a la ruta
        if ($request->user()->type == 'A') {
            return $next($request);
        }else{
            return redirect()->guest('/');
        }


    }
}
