<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Requests\User\ResetPasswordRequest;

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
        return view('auth.user.passwords.reset');
    }

    // パスワード変更処理
    public function reset(ResetPasswordRequest $request)
    {
        \Log::debug('resetメソッドです。');
        // ユーザーを特定するための情報を取得
        $email = $request->email; // ユーザーのメールアドレス
        $password = $request->newPassword; // 新しいパスワード

        // ユーザーモデルを取得
        $user = User::where('email', $email)->first();

        // パスワードを更新
        $user->password = Hash::make($password);
        $user->save();
    }
}