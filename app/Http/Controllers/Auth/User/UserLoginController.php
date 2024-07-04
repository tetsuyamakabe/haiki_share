<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserLoginController extends Controller
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

    // ユーザー情報の取得
    public function getUser(Request $request)
    {
        $user = $request->user();
        return Auth::user();
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        try {
            // DBからemailをキーにしてユーザー情報を取得
            $email = $request->input('email');
            $user = User::where('email', $email)->first();
            // ユーザーが見つからない場合とroleがコンビニユーザーの場合はエラーを返す
            if (!$user || $user->role == 'convenience') {
                return response()->json(['errors' => ['email' => ['メールアドレスかパスワードが間違っています。']]], 422);
            }
            // ユーザーIDを取得
            $userId = $user->id;
            // ユーザーのroleを取得
            $role = $user->role;
            if (($role === 'user')) {
                // 次回ログインを省略するチェックボックスにチェックが入っているか？
                $remember = $request->input('remember', false);
                if (Auth::attempt(['email' => $email, 'password' => $request->input('password')], $remember)) {
                    return response()->json(['message' => '認証に成功しました。', 'user_id' => $userId]);
                } else {
                    return response()->json(['message' => '認証に失敗しました。'], 401);
                }
            } else {
                return response()->json(['message' => 'ログインできません。'], 401);
            }
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'ログインできませんでした。'], 500);
        }
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        try {
            Auth::logout(); // ログアウト
            return response()->json(['message' => 'ログアウトしました。']);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'ログアウトできませんでした。'], 500);
        }
    }
}