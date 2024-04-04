<?php

namespace App\Http\Controllers\Auth\Convenience;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/convenience/mypage/{$userId}'; // ログイン後、マイページに遷移

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
        \Log::debug('$userは、', [$user]);
        $userId = $user->id;
        \Log::debug('ユーザーIDは、' . $userId);

        // ユーザーのroleを取得
        $role = $user->role;
        \Log::debug('ユーザーのroleは、' . $role);

        if (($role === 'convenience')) {
            \Log::debug('ログインします');
            if ($this->attemptLogin($request)) {
                \Log::debug('attemptLoginメソッド');
                // ログイン成功時にユーザーIDを含んだURLにリダイレクト
                return response()->json(['user_id' => $userId]);
            }
        } else {
            \Log::debug('ログインできません');
            return redirect()->route('convenience.login.show');
        }
    }
}