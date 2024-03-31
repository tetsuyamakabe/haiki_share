<?php

namespace App\Http\Controllers\Auth\Convenience;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Convenience\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    // ログイン画面の表示
    public function show(Request $request)
    {
        return view('auth.convenience.login');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        // DBからemailをキーにして取得
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        $userId = $user->id;
        \Log::debug('ユーザーIDは、' . $userId);

        // ユーザーのroleを取得
        $role = $user->role;
        \Log::debug('ユーザーのroleは、' . $role);

        if (($role === 'convenience')) {
            \Log::debug('ログインします');
            if ($this->attemptLogin($request)) {
                return redirect()->route('home'); // 【TODO】 /homeになっているので修正（Authミドルウェアのことを忘れない）
            }
        } else {
            \Log::debug('ログインできません');
            return redirect()->route('convenience.login.show');
        }
    }
}
