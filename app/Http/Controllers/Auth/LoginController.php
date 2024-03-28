<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($this->attemptLogin($request)) {
            // ログインが成功した場合は、ユーザー情報を取得する
            $user = $this->guard()->user();
            // $user = Auth::user()->id;
            // ユーザーがログインしているかどうかを確認
            if ($user) {
                $userId = $user->id;
                \Log::debug('ユーザーIDは、' . $userId);
                // ユーザーのroleを取得
                $role = $user->role;
                \Log::debug('ユーザーのroleは、' . $role);

                // クエリパラメータからtypeを取得
                $type = $request->query('type');
                \Log::debug('クエリパラメータは、' . $type);

                if (($role === 'user' && $type === 'user') || ($role === 'convenience' && $type === 'convenience')) {
                    // roleとクエリパラメータが整合する場合はログインを許可する
                    \Log::debug('ログインします');
                    return $this->sendLoginResponse($request);
                } else {
                    // roleとクエリパラメータが整合しない場合はログインを許可しない
                    \Log::debug('ログインできません');
                    return redirect('login');
                }
            } else {
                // ユーザーがログインしていない場合はログインできません
                return redirect('login');
            }
        }
    }
}
