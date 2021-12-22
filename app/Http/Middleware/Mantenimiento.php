<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Mantenimiento
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
        $estado = DB::table('mantenimiento')->first();
        if ($estado->estado == 'activo') {
            return $next($request);
        } else {
            if (Auth::user()->hasRole($estado->rango) || Auth::user()->hasRole('Owner')) {
                return $next($request);

            } else {
                return redirect('mantenimiento-view');
            }
        }
    }
}
