<?php

namespace App\Http\Middleware;

use Closure;

class isOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->isOwner()){
            return $next($request);
        }
        abort(403);
    }
}
