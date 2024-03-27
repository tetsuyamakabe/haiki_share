<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // ログインしているかどうかを確認
        if(auth()->check()) {
            // ログインユーザーのroleが'user'であるかどうかを確認
            if(auth()->user()->role === 'user') {
                return $next($request);
            }
        }

        return redirect()->route('home');
    }
}
