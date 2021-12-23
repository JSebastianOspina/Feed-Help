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
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $deckStatus = DB::table('systems')->first();
        if (!$deckStatus) { //haven't run migrations, let them pass.
            return $next($request);
        }
        //It's not enabled
        if ($deckStatus->enabled !== true) {
            // lets check the user role
            if (Auth::user()->admittedOnMaintenance()) {
                return $next($request);
            }
            //not allowed, redirect to error route.
            return redirect('mantenimiento-view');
        }
        //If enabled, let them pass
        return $next($request);
    }
}
