<?php

namespace App\Http\Middleware;

use Closure;

class AuthCheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->role === 'user') {
            return $next($request);
        }
    
        return redirect()->route('home');
    }
}
