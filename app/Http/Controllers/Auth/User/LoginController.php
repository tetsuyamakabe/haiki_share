<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        return view('auth.user.login');
    }

    public function login(Request $request)
{
    // DBからemailをキーにして取得
    $email = $request->input('email');
    $user = User::where('email', $email)->first();

    if(!$user) {
        \Log::debug('ユーザーが見つかりませんでした。');
        return redirect()->route('user.login.show')->with('error', 'ユーザーが見つかりませんでした。');
    }

    $userId = $user->id;
    \Log::debug('ユーザーIDは、' . $userId);

    // ユーザーのroleを取得
    $role = $user->role;
    \Log::debug('ユーザーのroleは、' . $role);

    // クエリパラメータからtypeを取得
    $type = $request->input('type');
    \Log::debug('クエリパラメータは、' . $type);

    if (($role === 'user' && $type === 'user')) {
        \Log::debug('ログインします');
        if ($this->attemptLogin($request)) {
            return redirect()->route('home');
        }
    } else {
        \Log::debug('ログインできません');
        return redirect()->route('user.login.show');
    }
}

    
}
