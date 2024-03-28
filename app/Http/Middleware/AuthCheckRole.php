<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        Log::debug('ミドルウェア');

        // ログインしているかどうかを確認
        if(!auth()->check()) {
            return redirect()->route('home')->withErrors('Unauthorized');
        }
        Log::debug('ログイン');
        // ログインユーザーのroleが'user'であるかどうかを確認
        $user = auth()->user();
        if($user->role === 'user' && $request->query('type') === 'user') {
            return $next($request);
        } elseif ($user->role === 'convenience' && $request->query('type') === 'convenience') {
            return $next($request);
        }
        return $next($request);
    }
}

