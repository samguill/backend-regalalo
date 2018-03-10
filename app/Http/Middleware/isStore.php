<?php

namespace App\Http\Middleware;

use Closure;

class isStore
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
        //Valida si es tienda para acceder a la ruta
        if ($request->user()->type == 'S') {
            return $next($request);
        }else{
            return redirect()->guest('/');
        }
    }
}
