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
        // リクエストからパスを取得してログに記録
        $path = $request->path();
        // リクエストされたパスによって適切なリダイレクト先を設定
        if ($path === 'user/login') {
            return route('user.login'); // 利用者ログイン画面にリダイレクト
        } elseif ($path === 'convenience/login') {
            return route('convenience.login'); // コンビニログイン画面にリダイレクト
        }
    }
}
