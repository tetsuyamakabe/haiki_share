<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        Log::debug('Authミドルウェア');

        // リクエストからルート名を取得してログに記録
        $routeName = $request->route()->getName();
        Log::debug('リクエストのルート名は、: ' . $routeName);

        // リクエストされたルート名によって適切なリダイレクト先を設定
        if ($routeName === 'user.login.show') {
            return route('user.login'); // 利用者ログイン画面にリダイレクト
        } elseif ($routeName === 'convenience.login.show') {
            return route('convenience.login'); // コンビニログイン画面にリダイレクト
        }
    }
}
