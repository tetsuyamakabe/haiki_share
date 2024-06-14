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
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    // ログイン処理
    public function login(LoginRequest $request)
    {
        try {
            // DBからemailをキーにしてユーザー情報を取得
            $email = $request->input('email');
            $user = User::where('email', $email)->first();
            // ユーザーが見つからない場合とroleが利用者ユーザーの場合はエラーを返す
            if (!$user || $user->role == 'user') {
                return response()->json(['errors' => ['email' => ['メールアドレスかパスワードが間違っています。']]], 422);
            }
            // ユーザーIDを取得
            $userId = $user->id;
            // ユーザーのroleを取得
            $role = $user->role;
            if (($role === 'convenience')) {
                // 次回ログインを省略するチェックボックスにチェックが入っているか？
                $remember = $request->input('remember', false);
                if (Auth::attempt(['email' => $email, 'password' => $request->input('password')], $remember)) {
                    // ログイン成功時にユーザーIDを含んだURLにリダイレクト
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
            Auth::logout();
            return response()->json(['message' => 'ログアウトしました。']);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'ログアウトできませんでした。'], 500);
        }
    }
}