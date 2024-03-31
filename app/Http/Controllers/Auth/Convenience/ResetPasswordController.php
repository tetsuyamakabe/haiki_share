<?php

namespace App\Http\Controllers\Auth\Convenience;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Requests\Convenience\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // パスワード変更画面（古いパスワード・新しいパスワード入力画面）の表示
    public function show(Request $request)
    {
        return view('auth.convenience.passwords.reset');
    }

    // パスワード変更処理
    public function reset(ResetPasswordRequest $request)
    {
        // 引数にResetPasswordRequestを渡してバリデーションメッセージを表示する
        // 変更処理はResetsPasswordsトレイトで実行
    }
}
