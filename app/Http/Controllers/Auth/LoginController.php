<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
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
        // DBからemailをキーにして取得
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        $userId = $user->id;
        \Log::debug('ユーザーIDは、' . $userId);

        // ユーザーのroleを取得
        $role = $user->role;
        \Log::debug('ユーザーのroleは、' . $role);

        // クエリパラメータからtypeを取得
        $type = $request->query('type');
        \Log::debug('クエリパラメータは、' . $type);
    
        if (($role === 'user' && $type === 'user') || ($role === 'convenience' && $type === 'convenience')) {
            \Log::debug('ログインします');
            if ($this->attemptLogin($request)) {
                return route('home');
                // return $this->sendLoginResponse($request);
            }
        } else {
            \Log::debug('ログインできません');
            // Missing required parameters for [Route: login] [URI: login?type={$type}].エラー
            return redirect('login')->with('type', $type);
            // return redirect('login?type=' . $type);
            // return redirect()->route('login.show', ['type' => $type]);
        }
    }
    
}
