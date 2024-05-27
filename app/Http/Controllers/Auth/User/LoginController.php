<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\LoginRequest;
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
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        \Log::debug('Request data:', $request->all());
        // DBからemailをキーにしてユーザー情報を取得
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        \Log::debug('$userは、', [$user]);
        // ユーザーが見つからない場合
        if (!$user) {
            return response()->json(['message' => 'ユーザーが見つかりません'], 404);
        }
        // roleがconvenienceの場合は422エラーを返す
        if ($user->role == 'convenience') {
            return response()->json(['errors' => ['email' => ['このメールアドレスは利用者側のメールアドレスではありません。']]], 422);
        }
        // ユーザーIDを取得
        $userId = $user->id;
        \Log::debug('ユーザーIDは、' . $userId);
        // ユーザーのroleを取得
        $role = $user->role;
        \Log::debug('ユーザーのroleは、' . $role);
        if (($role === 'user')) {
            \Log::debug('ログインします');
            // 次回ログインを省略するチェックボックスにチェックが入っているか？
            $remember = $request->input('remember', false);
            \Log::info('rememberは、', [$remember]);
            if (Auth::attempt(['email' => $email, 'password' => $request->input('password')], $remember)) {
                \Log::debug('attemptLoginメソッド');
                return response()->json(['message' => '認証に成功しました', 'user_id' => $userId]);
            } else {
                return response()->json(['message' => '認証に失敗しました'], 401);
            }
        } else {
            \Log::debug('ログインできません');
            return response()->json(['message' => 'ログインできません'], 401);
        }
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout(); // ログアウト
        return response()->json(['message' => 'ログアウトしました']);
    }
}