<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $system = DB::table('systems')->first();
        if (!$system) { //haven't run migrations, let them pass.
            return $next($request);
        }
        // lets check the user is Allowed
        if (Auth::user()->admittedOnMaintenance($system->status)) {
            return $next($request);
        }
        //not allowed, redirect to error route.
        return redirect('mantenimiento-view');
    }


}
